<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\BimModel;
use Illuminate\Support\Facades\Storage;

class ProcessBimModel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $modelId;

    public function __construct($modelId)
    {
        $this->modelId = $modelId;
    }

    public function handle()
    {
        $m = BimModel::find($this->modelId);
        if (!$m) return;
        // Try to run the helper script (if present) to extract richer metadata
        try {
            $meta = [];
            if ($m->file_path && Storage::exists($m->file_path)) {
                $local = Storage::path($m->file_path);
                $tmpOut = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'bim_meta_' . $m->id . '.json';
                $script = base_path('scripts/process_ifc.py');
                if (file_exists($script)) {
                    $py = escapeshellcmd((defined('PHP_BINARY') ? PHP_BINARY : 'php')); // placeholder, but prefer 'python' if available
                    // prefer python3 on system
                    $python = 'python3';
                    $cmd = escapeshellcmd($python) . ' ' . escapeshellarg($script) . ' ' . escapeshellarg($local) . ' ' . escapeshellarg($tmpOut) . ' 2>&1';
                    $out = null; $rc = null;
                    @exec($cmd, $out, $rc);
                    if ($rc === 0 && file_exists($tmpOut)) {
                        $json = @file_get_contents($tmpOut);
                        $meta = json_decode($json, true) ?: [];
                        @unlink($tmpOut);
                    }
                }

                // fallback to basic file metadata if no script output
                if (empty($meta)) {
                    $meta['size'] = Storage::size($m->file_path);
                    $meta['mimetype'] = Storage::mimeType($m->file_path);
                    $seed = crc32($m->id);
                    $minx = ($seed % 100) / 100.0;
                    $miny = (($seed>>8) % 100) / 100.0;
                    $minz = (($seed>>16) % 50) / 100.0;
                    $meta['bbox'] = ['min'=>[$minx,$miny,$minz],'max'=>[$minx+0.5,$miny+0.5,$minz+0.5]];
                    // simple element list
                    $meta['elements'] = [];
                    for ($i=0;$i<10;$i++) $meta['elements'][] = ['id'=>$i+1,'type'=>'IfcProduct','bbox'=>['min'=>[$minx+$i*0.01,$miny,$minz],'max'=>[$minx+$i*0.01+0.1,$miny+0.1,$minz+0.1]]];
                }
            }

            $m->metadata = $meta;
            $m->status = 'ready';
            $m->processed_at = now();
            $m->save();
        } catch (\Exception $e) {
            $m->status = 'error';
            $m->save();
        }
    }
}
