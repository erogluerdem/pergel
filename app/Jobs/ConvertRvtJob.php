<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use App\Models\BimModel;

class ConvertRvtJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public $modelId;

    public function __construct($modelId)
    {
        $this->modelId = $modelId;
    }

    public function handle()
    {
        $m = BimModel::find($this->modelId);
        if (! $m) return;

    // mark processing (use existing enum value)
    $m->status = 'processing';
        $m->save();

        // In a real implementation we'd call a conversion service (Revit -> IFC)
        // For now create a placeholder IFC file or copy a template if exists
        $template = storage_path('app/bim/models/placeholder_template.ifc');
        $outPath = 'bim/models/' . time() . '_converted.ifc';
        if (file_exists($template)) {
            Storage::put($outPath, file_get_contents($template));
        } else {
            // minimal placeholder content to let web-ifc-viewer try to parse
            $sample = "ISO-10303-21;\nHEADER;\nENDSEC;\nDATA;\nENDSEC;\nEND-ISO-10303-21;\n";
            Storage::put($outPath, $sample);
        }

        // update model to point to new IFC
    $m->file_path = $outPath;
    $m->file_type = 'ifc';
    $m->status = 'ready';
        $m->version_notes = ($m->version_notes ? $m->version_notes."\n" : '') . 'Converted from RVT (placeholder)';
        $m->save();
    }
}
