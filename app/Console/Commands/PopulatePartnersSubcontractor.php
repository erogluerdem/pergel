<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PopulatePartnersSubcontractor extends Command
{
    protected $signature = 'partners:map-subcontractors {--write : Actually persist changes} {--limit=0 : Limit number of partners to process (0 = all)}';
    protected $description = 'Heuristically map partners to subcontractors by name/alias and populate partners.subcontractor_id (dry-run by default).';

    public function handle()
    {
        if (!\Schema::hasTable('partners')) {
            $this->error('partners table not found.');
            return 1;
        }

    $limit = (int) $this->option('limit');
    // Select alias only when column exists to avoid SQL errors in older schemas
    $selectCols = ['id','name'];
    if (\Schema::hasColumn('partners', 'alias')) $selectCols[] = 'alias';
    $query = DB::table('partners')->select($selectCols);
        if ($limit > 0) $query->limit($limit);
        $partners = $query->get();

        $this->info('Loaded ' . $partners->count() . ' partners to process.');

        $written = 0;
        foreach ($partners as $p) {
            $name = trim($p->name ?? '');
            $alias = Schema::hasColumn('partners', 'alias') ? trim($p->alias ?? '') : '';

            if (empty($name) && empty($alias)) continue;

            // Normalize
            $norms = array_filter([Str::lower($name), Str::lower($alias)]);
            $norms = array_map(function($v){ return preg_replace('/[^a-z0-9çğıöşü\s]/u','', $v); }, $norms);

            // Try exact match in subcontractors table
            $match = DB::table('subcontractors')
                ->whereRaw('LOWER(TRIM(company_name)) = ?', [Str::lower($name)])
                ->orWhereRaw('LOWER(TRIM(company_name)) = ?', [Str::lower($alias)])
                ->first();

            if (!$match) {
                // Try contains match
                foreach ($norms as $n) {
                    if (empty($n)) continue;
                    $m = DB::table('subcontractors')->where('company_name', 'like', '%'.$n.'%')->first();
                    if ($m) { $match = $m; break; }
                }
            }

            if ($match) {
                $this->line("Partner #{$p->id} -> Subcontractor #{$match->id} ({$match->company_name})");
                if ($this->option('write')) {
                    DB::table('partners')->where('id', $p->id)->update(['subcontractor_id' => $match->id]);
                    $written++;
                }
            } else {
                $this->line("Partner #{$p->id} -> no match (name: {$p->name})");
            }
        }

        $this->info('Done. Persisted: ' . $written);
        return 0;
    }
}
