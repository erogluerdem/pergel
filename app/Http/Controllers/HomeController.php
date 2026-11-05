<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Notification;
use App\Models\PlanningTask;
use App\Models\Project;
use App\Models\Voucher;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function root(Request $request)
    {
        // Root route should prepare the same data as index(), so reuse it
        return $this->index($request);
    }

    public function index(Request $request)
    {
        // Eğer kullanıcı giriş yapmamışsa login ekranını göster
        if (!auth()->check()) {
            return view('auth-login');
        }
        // Ana sayfa dashboard'ını göster
        $user = auth()->user();
        $hour = now()->hour;

        if ($hour >= 6 && $hour < 12) {
            $greeting = 'Günaydın';
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = 'İyi günler';
        } elseif ($hour >= 18 && $hour < 24) {
            $greeting = 'İyi akşamlar';
        } else {
            $greeting = 'Günaydın';
        }

        $quotes = [
            "Hayat bisiklet sürmeye benzer. Dengenizi kaybetmemek için hareket etmelisiniz. - Albert Einstein",
            "Hayatta en hakiki mürşit ilimdir. - Mustafa Kemal Atatürk",
            "Bilgi güçtür. - Francis Bacon",
            "Değişim tek değişmez şeydir. - Herakleitos",
            "Düşünceleriniz sizi şekillendirir. - James Allen",
            "Başarı, başarısızlıklardan ders çıkarmaktır. - Winston Churchill",
            "Hayal et, inan, başar. - Walt Disney",
            "Zaman en değerli varlıktır. - Benjamin Franklin",
            "Cesaret, korkuyu hissetmek ama yine de ilerlemektir. - Nelson Mandela",
            "Öğrenmek, unutmamaktır. - Confucius",
            "Hayat, yaşadığınız değil, hatırladığınız şeydir. - Gabriel García Márquez",
            "Mutluluk, hedef değil yolculuktur. - Aristoteles",
            "Kendinizi sevin, çünkü siz benzersizsiniz. - Oscar Wilde",
            "Başarı, hazırlık ve fırsatın buluşmasıdır. - Seneca",
            "Hayalleriniz yoksa, hiçbir şeyiniz yoktur. - Sigmund Freud",
            "Değişim, büyümenin anahtarıdır. - Charles Darwin",
            "Cesaret, korkuyu yenmektir. - Mark Twain",
            "Bilgi, özgürlüktür. - Socrates",
            "Hayat, bir serüvendir. - Helen Keller",
            "İmkansız, sadece bir kelimedir. - Audrey Hepburn",
            "Başarı, düşüşlerden sonra kalkmaktır. - Vince Lombardi",
            "Zaman, en büyük öğretmendir. - Euripides",
            "Kendinizi keşfedin. - Socrates",
            "Hayat, bir seçimdir. - Stephen R. Covey",
            "Mutluluk, içsel bir durumdur. - Dalai Lama",
            "Değişim, fırsatları getirir. - Sun Tzu",
            "Bilgi, sonsuzdur. - Leonardo da Vinci",
            "Cesaret, zayıflığı yenmektir. - Thomas Jefferson",
            "Hayaller, gerçekleri yaratır. - Walt Disney",
            "Öğrenmek, yaşamaktır. - John Dewey",
            "Başarı, tutkuyla başlar. - Oprah Winfrey",
            "Zaman, iyileştirir. - William Shakespeare",
            "Kendinizi geliştirin. - Jim Rohn",
            "Hayat, bir yolculuktur. - Ralph Waldo Emerson",
            "Mutluluk, paylaşmaktır. - Mother Teresa",
            "Değişim, ilerlemedir. - Isaac Newton",
            "Bilgi, güç verir. - Thomas Jefferson",
            "Cesaret, sınırları aşmaktır. - Amelia Earhart",
            "Hayaller, hedefleri belirler. - Napoleon Hill",
            "Öğrenmek, keşfetmektir. - Albert Einstein",
            "Başarı, azimle gelir. - Thomas Edison",
            "Zaman, değerli bir hediyedir. - Steve Jobs",
            "Kendinizi aşın. - Aristotle",
            "Hayat, bir sanat eseridir. - Friedrich Nietzsche",
            "Mutluluk, basit şeylerde bulunur. - Paulo Coelho",
            "Değişim, evrimdir. - Charles Darwin",
            "Bilgi, aydınlıktır. - Victor Hugo",
            "Cesaret, özgürlüktür. - Mahatma Gandhi",
            "Hayaller, gerçek olur. - Henry David Thoreau",
            "Öğrenmek, büyümektir. - Maya Angelou",
            "Başarı, çalışmanın meyvesidir. - Thomas Jefferson",
            "Zaman, fırsatları getirir. - Benjamin Franklin",
        ];

        $ambientSounds = [
            [
                'name' => 'Yağmur Sesi',
                'url' => 'https://www.soundjay.com/misc/sounds/rain-03.mp3',
                'icon' => 'fas fa-cloud-rain'
            ],
            [
                'name' => 'Okyanus Dalgaları',
                'url' => 'https://www.soundjay.com/misc/sounds/ocean-wave-1.mp3',
                'icon' => 'fas fa-water'
            ],
            [
                'name' => 'Ormanda Kuş Sesleri',
                'url' => 'https://www.soundjay.com/misc/sounds/birds-01.mp3',
                'icon' => 'fas fa-tree'
            ],
            [
                'name' => 'Şömine Çıtırısı',
                'url' => 'https://www.soundjay.com/misc/sounds/fireplace-1.mp3',
                'icon' => 'fas fa-fire'
            ],
            [
                'name' => 'Rüzgar Sesi',
                'url' => 'https://www.soundjay.com/misc/sounds/wind-01.mp3',
                'icon' => 'fas fa-wind'
            ]
        ];

        $radioStations = [
            [
                'name' => 'RADYO ODTÜ CANLI',
                'url' => 'https://stream.radyoodtu.com.tr/canli',
                'genre' => 'Çeşitli',
                'country' => 'Türkiye'
            ],
            [
                'name' => 'RADYO ODTÜ EASY',
                'url' => 'https://stream.radyoodtu.com.tr/easy',
                'genre' => 'Easy Listening',
                'country' => 'Türkiye'
            ],
            [
                'name' => 'RADYO ODTÜ HİT',
                'url' => 'https://stream.radyoodtu.com.tr/hit',
                'genre' => 'Pop',
                'country' => 'Türkiye'
            ],
            [
                'name' => 'RADYO ODTÜ KARIŞIK KASET',
                'url' => 'https://stream.radyoodtu.com.tr/karisikkaset',
                'genre' => "80'ler-90'lar",
                'country' => 'Türkiye'
            ],
            [
                'name' => 'RADYO ODTÜ ROCK',
                'url' => 'http://stream.radyoodtu.com.tr/rock',
                'genre' => 'Rock',
                'country' => 'Türkiye'
            ],
            [
                'name' => 'KRAL FM',
                'url' => 'http://46.20.3.204/;type=mp3',
                'genre' => 'Pop',
                'country' => 'Türkiye'
            ],
            [
                'name' => 'KRAL POP',
                'url' => 'http://46.20.3.201/;type=mp3',
                'genre' => 'Pop',
                'country' => 'Türkiye'
            ],
            [
                'name' => 'KAFA RADYO',
                'url' => 'http://37.247.98.17/',
                'genre' => 'Alternatif',
                'country' => 'Türkiye'
            ],
        ];

        Carbon::setLocale('tr');

        $dashboardData = $this->buildDashboardData();

        return view('index', array_merge(
            compact('user', 'greeting', 'quotes', 'ambientSounds', 'radioStations'),
            $dashboardData
        ));
    }

    protected function buildDashboardData(): array
    {
        $now = now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $previousMonthStart = $currentMonthStart->copy()->subMonth();
        $previousMonthEnd = $currentMonthStart->copy()->subDay();
        $twoMonthsAgoStart = $currentMonthStart->copy()->subMonths(2);
        $twoMonthsAgoEnd = $previousMonthStart->copy()->subDay();

        $invoices = Invoice::select('amount', 'paid_amount', 'date', 'created_at')->get();
        $vouchers = Voucher::select('type', 'amount', 'date', 'created_at', 'no')->get();
        $projects = Project::select('name', 'budget', 'type', 'health_status', 'progress_percent', 'start_date', 'end_date')->get();
        $planningTasks = PlanningTask::select('name', 'start_date', 'end_date')->whereNotNull('start_date')->whereNotNull('end_date')->orderBy('start_date')->limit(7)->get();

        $resolveDate = function ($model, string $primary = 'date') {
            $value = $model->{$primary} ?? null;
            if ($value instanceof Carbon) {
                return $value->copy();
            }
            if ($value) {
                return Carbon::parse($value);
            }
            $created = $model->created_at ?? null;
            if ($created instanceof Carbon) {
                return $created->copy();
            }
            return $created ? Carbon::parse($created) : null;
        };

        $invoiceTotal = (float) $invoices->sum('amount');
        $invoiceCollected = (float) $invoices->sum('paid_amount');
        $collectionRate = $invoiceTotal > 0 ? round(($invoiceCollected / $invoiceTotal) * 100, 1) : 0;

        $currentMonthInvoices = (float) $this->sumByDateRange($invoices, $resolveDate, 'amount', $currentMonthStart, $now);
        $previousMonthInvoices = (float) $this->sumByDateRange($invoices, $resolveDate, 'amount', $previousMonthStart, $previousMonthEnd);
        $twoMonthsAgoInvoices = (float) $this->sumByDateRange($invoices, $resolveDate, 'amount', $twoMonthsAgoStart, $twoMonthsAgoEnd);

        $collectionVouchers = $vouchers->filter(fn ($voucher) => $voucher->type === 'collection');
        $paymentVouchers = $vouchers->filter(fn ($voucher) => $voucher->type === 'payment');

        $currentMonthCollections = (float) $this->sumByDateRange($collectionVouchers, $resolveDate, 'amount', $currentMonthStart, $now);
        $previousMonthCollections = (float) $this->sumByDateRange($collectionVouchers, $resolveDate, 'amount', $previousMonthStart, $previousMonthEnd);
        $twoMonthsAgoCollections = (float) $this->sumByDateRange($collectionVouchers, $resolveDate, 'amount', $twoMonthsAgoStart, $twoMonthsAgoEnd);

        $salesSummary = [
            'total' => [
                'value' => $invoiceTotal,
                'collected' => $invoiceCollected,
                'collectionRate' => $collectionRate,
            ],
            'currentMonthInvoices' => [
                'value' => $currentMonthInvoices,
                'change' => $this->calculateChange($currentMonthInvoices, $previousMonthInvoices),
            ],
            'currentMonthCollections' => [
                'value' => $currentMonthCollections,
                'change' => $this->calculateChange($currentMonthCollections, $previousMonthCollections),
            ],
        ];

        $months = collect(CarbonPeriod::create($now->copy()->subMonths(11)->startOfMonth(), '1 month', $currentMonthStart));
        $monthKeys = $months->map(fn (Carbon $date) => $date->format('Y-m'));
        $invoiceMonthly = array_fill_keys($monthKeys->all(), 0.0);
        $collectionMonthly = $invoiceMonthly;
        $paymentMonthly = $invoiceMonthly;

        foreach ($invoices as $invoice) {
            $date = $resolveDate($invoice);
            if (!$date) {
                continue;
            }
            $key = $date->copy()->startOfMonth()->format('Y-m');
            if (array_key_exists($key, $invoiceMonthly)) {
                $invoiceMonthly[$key] += (float) $invoice->amount;
            }
        }

        foreach ($collectionVouchers as $voucher) {
            $date = $resolveDate($voucher);
            if (!$date) {
                continue;
            }
            $key = $date->copy()->startOfMonth()->format('Y-m');
            if (array_key_exists($key, $collectionMonthly)) {
                $collectionMonthly[$key] += (float) $voucher->amount;
            }
        }

        foreach ($paymentVouchers as $voucher) {
            $date = $resolveDate($voucher);
            if (!$date) {
                continue;
            }
            $key = $date->copy()->startOfMonth()->format('Y-m');
            if (array_key_exists($key, $paymentMonthly)) {
                $paymentMonthly[$key] += (float) $voucher->amount;
            }
        }

        $cashFlowMonthly = [];
        foreach ($monthKeys as $key) {
            $cashFlowMonthly[$key] = ($collectionMonthly[$key] ?? 0) - ($paymentMonthly[$key] ?? 0);
        }

        $monthLabels = $months->map(fn (Carbon $date) => $date->locale('tr')->isoFormat('MMM'));
        $monthIsoDates = $months->map(fn (Carbon $date) => $date->format('Y-m-01'));

        $projectHealthMap = [
            'healthy' => ['label' => 'Sağlıklı', 'color' => '#0ab39c'],
            'risky' => ['label' => 'Riskli', 'color' => '#f7b84b'],
            'critical' => ['label' => 'Kritik', 'color' => '#f06548'],
        ];

        $healthCounts = $projects->groupBy('health_status')->map->count();
        $totalProjects = $projects->count();
        $projectHealthSeries = [];
        $projectHealthLegend = [];
        foreach ($projectHealthMap as $key => $meta) {
            $count = (int) ($healthCounts[$key] ?? 0);
            $projectHealthSeries[] = $count;
            $percentage = $totalProjects > 0 ? round(($count / $totalProjects) * 100, 1) : 0;
            $projectHealthLegend[] = array_merge($meta, [
                'count' => $count,
                'percentage' => $percentage,
            ]);
        }

        if (array_sum($projectHealthSeries) === 0) {
            $projectHealthSeries = [1];
        }

        $typeTotals = $projects
            ->filter(fn ($project) => !empty($project->type))
            ->groupBy('type')
            ->map(fn (Collection $items) => (float) $items->sum('budget'))
            ->sortDesc()
            ->take(8);

        

        $topProjects = $projects
            ->sortByDesc(fn ($project) => $project->progress_percent ?? 0)
            ->take(7);

        if ($planningTasks->isEmpty()) {
            $planningTasks = Project::select('name', 'start_date', 'end_date')
                ->whereNotNull('start_date')
                ->whereNotNull('end_date')
                ->orderBy('start_date')
                ->limit(7)
                ->get();
        }

        $taskRanges = $planningTasks->map(function ($task) use ($resolveDate) {
            $start = $resolveDate($task, 'start_date');
            $end = $resolveDate($task, 'end_date');
            if (!$start || !$end) {
                return null;
            }
            return [
                'x' => $task->name,
                'y' => [$start->valueOf(), $end->valueOf()],
            ];
        })->filter()->values();

        

        $recentCollections = $collectionVouchers
            ->sortBy(fn ($voucher) => $resolveDate($voucher))
            ->take(10);

        $voucherCategories = $recentCollections->map(function ($voucher) use ($resolveDate) {
            $date = $resolveDate($voucher);
            $labelDate = $date ? $date->format('d.m') : '-';
            return ($voucher->no ?? 'Tahsilat') . ' · ' . $labelDate;
        })->values();

        $voucherAmounts = $recentCollections->map(fn ($voucher) => round((float) $voucher->amount, 2))->values();

        $dashboardCharts = [
            'salesFigures' => [
                'categories' => $monthLabels,
                'series' => [
                    ['name' => 'Fatura Tutarı', 'data' => array_values($invoiceMonthly)],
                    ['name' => 'Tahsilat', 'data' => array_values($collectionMonthly)],
                ],
            ],
            'cashFlow' => [
                'categories' => $monthIsoDates,
                'monthLabels' => $monthLabels,
                'series' => array_values($cashFlowMonthly),
                'collections' => array_values($collectionMonthly),
                'payments' => array_values($paymentMonthly),
                'totalCollections' => array_sum($collectionMonthly),
                'totalPayments' => array_sum($paymentMonthly),
                'netBalance' => array_sum($cashFlowMonthly),
            ],
            'projectHealth' => [
                'labels' => array_column($projectHealthLegend, 'label'),
                'series' => $projectHealthSeries,
            ],
            'projectTypes' => [
                'labels' => $typeTotals->keys()->values(),
                'series' => $typeTotals->values(),
            ],
            'voucherBar' => [
                'categories' => $voucherCategories,
                'series' => $voucherAmounts,
            ],
            'progressRadar' => [
                'categories' => $topProjects->map(fn ($project) => $project->name)->values(),
                'series' => [
                    [
                        'name' => 'Tamamlanma %',
                        'data' => $topProjects->map(fn ($project) => round((float) ($project->progress_percent ?? 0), 1))->values(),
                    ],
                ],
            ],
            'taskRanges' => [
                'series' => [
                    ['data' => $taskRanges],
                ],
            ],
        ];

        $notifications = Notification::orderByDesc('created_at')->limit(5)->get();
        $notificationList = $notifications->map(function ($notification) {
            return [
                'title' => $notification->title ?? 'Bildirim',
                'message' => $notification->message ?? '',
                'time' => optional($notification->created_at)->diffForHumans(),
                'priority' => $notification->priority ?? 'medium',
            ];
        })->values();

        

        $timeline = $notifications->take(4)->map(function ($notification) {
            $time = optional($notification->created_at);
            $statusMap = [
                'urgent' => 'danger',
                'high' => 'success',
                'medium' => 'warning',
                'low' => 'info',
            ];
            return [
                'time' => $time ? $time->format('H:i') : '--:--',
                'title' => $notification->title ?? 'Etkinlik',
                'description' => $notification->message ?? '',
                'status' => $statusMap[$notification->priority ?? 'medium'] ?? 'primary',
            ];
        })->values();

        

        return [
            'salesSummary' => $salesSummary,
            'projectHealthLegend' => $projectHealthLegend,
            'dashboardCharts' => $dashboardCharts,
            'notificationsFeed' => $notificationList,
            'timelineFeed' => $timeline,
        ];
    }

    protected function sumByDateRange($items, callable $resolveDate, string $amountField, Carbon $start, Carbon $end): float
    {
        return (float) $items->reduce(function ($carry, $item) use ($resolveDate, $amountField, $start, $end) {
            $date = $resolveDate($item);
            if (!$date) {
                return $carry;
            }
            if ($date->betweenIncluded($start, $end)) {
                return $carry + (float) ($item->{$amountField} ?? 0);
            }
            return $carry;
        }, 0);
    }

    protected function calculateChange(float $current, float $previous): array
    {
        if ($previous == 0.0) {
            $direction = $current >= 0 ? 'up' : 'down';
            $value = $current == 0.0 ? 0.0 : 100.0;
        } else {
            $change = (($current - $previous) / $previous) * 100;
            $direction = $change >= 0 ? 'up' : 'down';
            $value = round(abs($change), 1);
        }

        return [
            'direction' => $direction,
            'value' => $value,
        ];
    }
}
