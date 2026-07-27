<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Models\Client;
use App\Models\GrowthLog;
use App\Models\Hydroponics;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Site;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    use WithDateRange;

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function render()
    {
        $payload = Cache::remember($this->cacheKey(), 120, fn () => $this->buildDashboardData());

        return view('livewire.admin.dashboard', $payload);
    }

    protected function cacheKey(): string
    {
        return 'admin.dashboard.v4.'.$this->preset.'.'.$this->dateFrom.'.'.$this->dateTo;
    }

    protected function buildDashboardData(): array
    {
        $sites = Site::count();
        $hydroponics = Hydroponics::count();
        $products = Product::count();
        $lowStock = Product::where('quantity', '<=', 10)->count();

        $salesQuery = Sale::query();
        $this->applyDateFilter($salesQuery, 'sale_date');
        $salesCount = (clone $salesQuery)->count();
        $revenue = (float) (clone $salesQuery)->sum('total_price');

        $clients = Client::count();
        $visitorsQuery = Visitor::query();
        $this->applyDateFilter($visitorsQuery, 'created_at');
        $visitors = (clone $visitorsQuery)->count();

        $growthQuery = GrowthLog::query();
        $this->applyDateFilter($growthQuery, 'log_date');
        $avgTrays = (float) ((clone $growthQuery)->avg('growth_value') ?? 0);
        $growthLogs = (clone $growthQuery)->count();

        $salesOverTime = $this->salesOverTime();
        $salesBySite = $this->salesBySite();
        $recentSales = $this->recentSales();
        $palette = ['#053a06', '#0a5c0c', '#1a7a1d', '#2d9430', '#032804', '#4aaf4d', '#7bc67d', '#F59E0B'];

        $barCount = max(count($salesOverTime['labels']), 1);
        $barColors = array_map(
            fn ($i) => $i === $barCount - 1 ? '#053a06' : '#C5D9C5',
            range(0, $barCount - 1)
        );

        return [
            'primaryKpis' => [
                [
                    'label' => 'Revenue',
                    'value' => number_format($revenue, 0).' RWF',
                    'description' => number_format($salesCount).' sales in range',
                    'icon' => 'currency',
                    'tone' => 'emerald',
                ],
                [
                    'label' => 'Sites',
                    'value' => number_format($sites),
                    'description' => number_format($hydroponics).' categories',
                    'icon' => 'building',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Products',
                    'value' => number_format($products),
                    'description' => number_format($lowStock).' low stock (<=10 kg)',
                    'icon' => 'cube',
                    'tone' => 'amber',
                ],
                [
                    'label' => 'Clients',
                    'value' => number_format($clients),
                    'description' => number_format($visitors).' visitors in range',
                    'icon' => 'users',
                    'tone' => 'violet',
                ],
            ],
            'secondaryKpis' => [
                [
                    'label' => 'Growth logs',
                    'value' => number_format($growthLogs),
                    'description' => 'Avg trays '.number_format($avgTrays, 1),
                    'icon' => 'chart',
                    'tone' => 'emerald',
                ],
                [
                    'label' => 'Visitors',
                    'value' => number_format($visitors),
                    'description' => 'In selected period',
                    'icon' => 'eye',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Sales count',
                    'value' => number_format($salesCount),
                    'description' => 'Orders in selected period',
                    'icon' => 'cart',
                    'tone' => 'amber',
                ],
            ],
            'salesRevenueConfig' => [
                'type' => 'bar',
                'data' => [
                    'labels' => $salesOverTime['labels'],
                    'datasets' => [[
                        'label' => 'Revenue (RWF)',
                        'data' => $salesOverTime['revenue'],
                        'backgroundColor' => $barColors,
                        'borderRadius' => 10,
                        'borderSkipped' => false,
                        'maxBarThickness' => 28,
                    ]],
                ],
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'plugins' => [
                        'legend' => ['display' => false],
                        'tooltip' => [
                            'backgroundColor' => '#053a06',
                            'titleColor' => '#fff',
                            'bodyColor' => '#fff',
                            'padding' => 12,
                            'cornerRadius' => 10,
                            'displayColors' => false,
                        ],
                    ],
                    'scales' => [
                        'x' => [
                            'grid' => ['display' => false],
                            'ticks' => ['color' => '#94a3b8', 'maxRotation' => 0, 'autoSkip' => true, 'maxTicksLimit' => 8],
                        ],
                        'y' => [
                            'beginAtZero' => true,
                            'grid' => ['color' => 'rgba(15,23,42,0.05)', 'drawBorder' => false],
                            'ticks' => ['color' => '#94a3b8'],
                            'border' => ['display' => false],
                        ],
                    ],
                ],
            ],
            'salesBySiteConfig' => [
                'type' => 'doughnut',
                'data' => [
                    'labels' => $salesBySite['labels'],
                    'datasets' => [[
                        'data' => $salesBySite['values'],
                        'backgroundColor' => array_values(array_slice($palette, 0, max(count($salesBySite['labels']), 1))),
                        'borderWidth' => 0,
                        'hoverOffset' => 4,
                    ]],
                ],
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'plugins' => [
                        'legend' => [
                            'position' => 'bottom',
                            'labels' => [
                                'boxWidth' => 10,
                                'boxHeight' => 10,
                                'usePointStyle' => true,
                                'pointStyle' => 'circle',
                                'padding' => 14,
                                'color' => '#64748b',
                                'font' => ['size' => 11],
                            ],
                        ],
                    ],
                    'cutout' => '72%',
                ],
            ],
            'recentSales' => $recentSales,
            'chartGranularity' => $salesOverTime['granularity'],
            'hasSalesChartData' => count($salesOverTime['labels']) > 0,
            'hasSiteChartData' => count($salesBySite['labels']) > 0,
            'siteChartTotal' => array_sum($salesBySite['values']),
        ];
    }

    protected function recentSales()
    {
        $query = Sale::query()->with(['product.site', 'client']);
        $this->applyDateFilter($query, 'sale_date');

        return $query->orderByDesc('sale_date')->orderByDesc('id')->limit(6)->get();
    }

    protected function useMonthlyBuckets(): bool
    {
        if ($this->preset === 'all' || $this->dateFrom === '' || $this->dateTo === '') {
            return true;
        }

        $from = Carbon::parse($this->dateFrom);
        $to = Carbon::parse($this->dateTo);

        return $from->diffInDays($to) > 62;
    }

    protected function monthExpression(string $column): string
    {
        return DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', {$column})"
            : "DATE_FORMAT({$column}, '%Y-%m')";
    }

    protected function salesOverTime(): array
    {
        $monthly = $this->useMonthlyBuckets();
        $expr = $monthly
            ? $this->monthExpression('sale_date').' as bucket'
            : 'DATE(sale_date) as bucket';
        $group = $monthly
            ? DB::raw($this->monthExpression('sale_date'))
            : DB::raw('DATE(sale_date)');

        $query = Sale::query()
            ->selectRaw("{$expr}, SUM(total_price) as revenue")
            ->groupBy($group)
            ->orderBy('bucket');

        $this->applyDateFilter($query, 'sale_date');
        $rows = $query->get();

        return [
            'granularity' => $monthly ? 'month' : 'day',
            'labels' => $rows->pluck('bucket')->map(fn ($d) => (string) $d)->all(),
            'revenue' => $rows->pluck('revenue')->map(fn ($v) => (float) $v)->all(),
        ];
    }

    protected function salesBySite(): array
    {
        $query = Sale::query()
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->join('sites', 'products.site_id', '=', 'sites.id')
            ->select('sites.site_name', DB::raw('SUM(sales.total_price) as revenue'))
            ->groupBy('sites.id', 'sites.site_name')
            ->orderByDesc('revenue')
            ->limit(6);

        $this->applyDateFilter($query, 'sales.sale_date');
        $rows = $query->get();

        return [
            'labels' => $rows->pluck('site_name')->all(),
            'values' => $rows->pluck('revenue')->map(fn ($v) => (float) $v)->all(),
        ];
    }
}
