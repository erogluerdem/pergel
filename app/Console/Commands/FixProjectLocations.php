<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use DB;

class FixProjectLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * --fill : actually write demo locations
     * --force: bypass confirmation when filling
     *
     * @var string
     */
    protected $signature = 'projects:locations {--fill : Fill missing locations with demo coordinates} {--force : Apply changes without confirmation} {--backup : Backup previous locations before writing} {--restore : Restore last backups for listed projects} {--undo-demo : Restore backups only for demo locations previously written by this command}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List projects with missing or invalid location and optionally fill them with demo coordinates (development use only).';

    public function handle()
    {
    $this->info('Scanning projects for missing or invalid locations...');

        $projects = Project::all();

        $missing = collect();

        foreach ($projects as $p) {
            $loc = $p->location;

            $hasLatLng = false;

            if (is_array($loc)) {
                $hasLatLng = isset($loc['lat']) && isset($loc['lng']) && is_numeric($loc['lat']) && is_numeric($loc['lng']);
            } elseif (is_string($loc) && trim($loc) !== '') {
                // Try JSON decode if it's a JSON string
                $decoded = json_decode($loc, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $hasLatLng = isset($decoded['lat']) && isset($decoded['lng']) && is_numeric($decoded['lat']) && is_numeric($decoded['lng']);
                }
            }

            if (!$hasLatLng) {
                $missing->push($p);
            }
        }

        $count = $missing->count();
        if ($count === 0) {
            $this->info('No projects with missing or invalid locations were found.');
            return 0;
        }

        $this->warn("Found {$count} project(s) with missing/invalid locations:");

        $rows = $missing->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'location' => is_scalar($p->location) ? $p->location : json_encode($p->location),
            ];
        })->toArray();

        $this->table(['id','name','location'], $rows);

        if ($this->option('restore')) {
            return $this->handleRestore($missing);
        }

        if (!$this->option('fill')) {
            $this->info('No changes will be made. To fill missing locations with demo coordinates, re-run with --fill.');
            return 0;
        }

        $force = $this->option('force');

        if (!$force) {
            $confirm = $this->confirm('This will write demo coordinates into the projects listed above. This is intended for development/demo only. Continue?');
            if (!$confirm) {
                $this->info('Aborted. No changes made.');
                return 1;
            }
        }

        $doBackup = $this->option('backup');

        DB::beginTransaction();
        try {
            $centerLat = 39.0; // safe default center (Turkey)
            $centerLng = 35.0;
            $i = 0;

            foreach ($missing as $p) {
                // Create a slightly different coord per project to avoid exact overlap
                $offset = ($i % 10) * 0.03; // vary by 0..0.27
                $lat = $centerLat + $offset + (($p->id % 7) * 0.01);
                $lng = $centerLng - $offset - (($p->id % 11) * 0.01);

                $demoLocation = [
                    'lat' => round($lat, 6),
                    'lng' => round($lng, 6),
                    'label' => 'Demo coord (please update)',
                ];

                if ($doBackup) {
                    // store previous location in backups table
                    DB::table('project_location_backups')->insert([
                        'project_id' => $p->id,
                        'previous_location' => json_encode(is_scalar($p->location) ? $p->location : $p->location),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $p->location = $demoLocation;
                $p->save();
                $this->line("Updated project {$p->id} - {$p->name} -> lat: {$demoLocation['lat']}, lng: {$demoLocation['lng']}");
                $i++;
            }

            DB::commit();
            $this->info('Demo coordinates written successfully.');
            $this->warn('Remember to replace demo coordinates with real locations before using in production.');
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error while writing locations: ' . $e->getMessage());
            return 1;
        }
    }

    protected function handleRestore($missing)
    {
        // If no missing items provided, or when undo-demo requested,
        // find candidate projects by demo-label or backups.
        $undoDemoOnly = $this->option('undo-demo');

        $candidates = collect();

        if ($missing->count() > 0) {
            $candidates = $missing;
        }

        if ($undoDemoOnly || $candidates->isEmpty()) {
            // include projects that currently have our demo label
            $demoProjects = Project::all()->filter(function ($p) {
                $loc = $p->location;
                return is_array($loc) && isset($loc['label']) && $loc['label'] === 'Demo coord (please update)';
            });

            $candidates = $candidates->merge($demoProjects)->unique('id');
        }

        if ($candidates->count() === 0) {
            $this->info('No projects found to restore.');
            return 0;
        }

        $this->warn('Attempting restore from backups for the selected projects...');

        DB::beginTransaction();
        try {
            foreach ($candidates as $p) {
                // get last backup for project
                $backup = DB::table('project_location_backups')
                    ->where('project_id', $p->id)
                    ->orderByDesc('id')
                    ->first();

                if ($backup) {
                    $prev = json_decode($backup->previous_location, true);
                    $p->location = $prev;
                    $p->save();
                    $this->line("Restored project {$p->id} - {$p->name} from backup");
                    continue;
                }

                // No backup found: if undo-demo, and current location looks like demo, clear it
                $curr = $p->location;
                $hasDemoLabel = is_array($curr) && isset($curr['label']) && $curr['label'] === 'Demo coord (please update)';
                if ($hasDemoLabel) {
                    $p->location = null;
                    $p->save();
                    $this->line("Cleared demo location for project {$p->id} - {$p->name} (no backup available)");
                    continue;
                }

                $this->line("No backup and not a demo location for project {$p->id} - {$p->name}; skipped.");
            }

            DB::commit();
            $this->info('Restore/undo-demo complete.');
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error during restore: ' . $e->getMessage());
            return 1;
        }
    }
}
