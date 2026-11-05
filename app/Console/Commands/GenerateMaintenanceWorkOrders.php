<?php

namespace App\Console\Commands;

use App\Mail\MaintenanceReminder;
use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\MaintenanceTemplate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class GenerateMaintenanceWorkOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:generate-work-orders {--days=7 : Generate work orders for next N days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate maintenance work orders based on asset templates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysAhead = (int) $this->option('days');
        $targetDate = Carbon::now()->addDays($daysAhead);

        $this->info("Generating maintenance work orders for next {$daysAhead} days...");

        // Aktif template'lere sahip asset'leri al
        $assets = Asset::with('maintenanceTemplate')
            ->whereNotNull('maintenance_template_id')
            ->whereHas('maintenanceTemplate', function($query) {
                $query->where('is_active', true);
            })
            ->get();

        $generatedCount = 0;

        foreach ($assets as $asset) {
            $template = $asset->maintenanceTemplate;

            // Son bakım tarihini kontrol et
            $lastMaintenance = $asset->last_maintenance_date
                ? Carbon::parse($asset->last_maintenance_date)
                : ($asset->installation_date ? Carbon::parse($asset->installation_date) : Carbon::now()->subYears(1));

            // Bir sonraki bakım tarihini hesapla
            $nextMaintenanceDate = $this->calculateNextMaintenanceDate($lastMaintenance, $template);

            // Eğer next_maintenance_date varsa ve last'dan sonra ise onu kullan
            if ($asset->next_maintenance_date && Carbon::parse($asset->next_maintenance_date)->gt($lastMaintenance)) {
                $nextMaintenanceDate = Carbon::parse($asset->next_maintenance_date);
            }

            // Hedef tarih aralığında mı kontrol et (bugün dahil)
            if ($nextMaintenanceDate->gte(Carbon::now()->startOfDay()) && $nextMaintenanceDate->lte($targetDate)) {
                // Bu tarih için zaten iş emri var mı kontrol et
                $existingOrder = Maintenance::where('asset_id', $asset->id)
                    ->where('template_id', $template->id)
                    ->whereDate('scheduled_date', $nextMaintenanceDate->toDateString())
                    ->exists();

                if (!$existingOrder) {
                    // İş emri oluştur
                    Maintenance::create([
                        'asset_id' => $asset->id,
                        'template_id' => $template->id,
                        'type' => 'preventive',
                        'title' => $template->name,
                        'description' => $template->description,
                        'priority' => $template->priority,
                        'status' => 'pending',
                        'scheduled_date' => $nextMaintenanceDate,
                    ]);

                    $generatedCount++;
                    $this->line("Created work order for {$asset->name} - {$nextMaintenanceDate->format('Y-m-d')}");

                    // Bakım hatırlatma email'i gönder (3 gün içinde ise)
                    $daysUntilMaintenance = Carbon::now()->diffInDays($nextMaintenanceDate, false);
                    if ($daysUntilMaintenance <= 3 && $daysUntilMaintenance >= 0) {
                        try {
                            Mail::to('facility-manager@company.com')->send(new MaintenanceReminder($asset, $daysUntilMaintenance));
                            $this->line("Sent maintenance reminder email for {$asset->name}");
                        } catch (\Exception $e) {
                            $this->error("Failed to send email for {$asset->name}: " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $this->info("Generated {$generatedCount} maintenance work orders.");
    }

    private function calculateNextMaintenanceDate(Carbon $lastMaintenance, MaintenanceTemplate $template): Carbon
    {
        $value = (int) $template->frequency_value;

        switch ($template->frequency_unit) {
            case 'days':
                return $lastMaintenance->copy()->addDays($value);
            case 'weeks':
                return $lastMaintenance->copy()->addWeeks($value);
            case 'months':
                return $lastMaintenance->copy()->addMonths($value);
            case 'years':
                return $lastMaintenance->copy()->addYears($value);
            default:
                return $lastMaintenance->copy()->addMonths(1);
        }
    }
}
