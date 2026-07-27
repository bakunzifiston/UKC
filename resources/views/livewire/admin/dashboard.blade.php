<div>
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Dashboard</h2>
            <p class="mt-0.5 text-sm text-slate-400">Overview of sites, inventory, and sales.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="preset" class="ui-input cursor-pointer">
                <option value="all">All time</option>
                <option value="year">This year</option>
                <option value="quarter">This quarter</option>
                <option value="month">This month</option>
                <option value="week">This week</option>
                <option value="custom">Custom range</option>
            </select>
            @if ($preset === 'custom')
                <input type="date" wire:model.live="dateFrom" class="ui-input">
                <input type="date" wire:model.live="dateTo" class="ui-input">
            @endif
        </div>
    </div>

    <div class="relative space-y-5" wire:loading.class="opacity-60">
        <div wire:loading class="absolute right-0 top-0 z-10 rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 shadow">
            Updating…
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($primaryKpis as $kpi)
                <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
            @endforeach
        </div>

        <div class="grid gap-5 xl:grid-cols-5" wire:key="dash-charts-{{ md5($preset.$dateFrom.$dateTo) }}">
            <div class="ui-card p-5 xl:col-span-3">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Revenue</h3>
                        <p class="mt-0.5 text-xs text-slate-400">
                            {{ $chartGranularity === 'month' ? 'Monthly' : 'Daily' }} totals for the selected period
                        </p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-medium text-slate-500">
                        {{ ucfirst($preset === 'all' ? 'All time' : $preset) }}
                    </span>
                </div>
                @if ($hasSalesChartData)
                    <div
                        class="relative h-72"
                        x-data="{ config: {{ \Illuminate\Support\Js::from($salesRevenueConfig) }} }"
                        x-init="window.initAdminChart('salesRevenueChart', config)"
                    >
                        <canvas id="salesRevenueChart"></canvas>
                    </div>
                @else
                    <div class="flex h-72 items-center justify-center text-sm text-slate-400">No sales data for this range.</div>
                @endif
            </div>

            <div class="flex flex-col gap-5 xl:col-span-2">
                <div class="ui-card p-5">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-slate-900">By site</h3>
                        <p class="mt-0.5 text-xs text-slate-400">Revenue share</p>
                    </div>
                    @if ($hasSiteChartData)
                        <div class="relative mx-auto h-48 w-full max-w-[220px]">
                            <div
                                class="absolute inset-0"
                                x-data="{ config: {{ \Illuminate\Support\Js::from($salesBySiteConfig) }} }"
                                x-init="window.initAdminChart('salesBySiteChart', config)"
                            >
                                <canvas id="salesBySiteChart"></canvas>
                            </div>
                            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center pb-8">
                                <p class="text-[10px] font-medium uppercase tracking-wide text-slate-400">Total</p>
                                <p class="text-sm font-bold text-slate-900">{{ number_format($siteChartTotal / 1000, 0) }}k</p>
                            </div>
                        </div>
                    @else
                        <div class="flex h-48 items-center justify-center text-sm text-slate-400">No sales data.</div>
                    @endif
                </div>

                <div class="grid gap-3 sm:grid-cols-3 xl:grid-cols-1">
                    @foreach ($secondaryKpis as $kpi)
                        <x-kpi-card :label="$kpi['label']" :value="$kpi['value']" :description="$kpi['description']" :icon="$kpi['icon'] ?? 'chart'" :tone="$kpi['tone'] ?? 'emerald'" />
                    @endforeach
                </div>
            </div>
        </div>

        <div class="ui-card overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Recent sales</h3>
                    <p class="mt-0.5 text-xs text-slate-400">Latest orders in the selected period</p>
                </div>
                <a href="{{ route('admin.sales.index') }}" class="text-xs font-semibold text-brand hover:underline">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase tracking-wide text-slate-400">
                            <th class="px-5 py-3 font-medium">Date</th>
                            <th class="px-5 py-3 font-medium">Client</th>
                            <th class="px-5 py-3 font-medium">Product</th>
                            <th class="px-5 py-3 font-medium">Site</th>
                            <th class="px-5 py-3 font-medium text-right">Amount</th>
                            <th class="px-5 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse ($recentSales as $sale)
                            <tr class="hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-600">{{ optional($sale->sale_date)->format('Y-m-d') }}</td>
                                <td class="px-5 py-3.5 font-medium text-slate-800">{{ $sale->client?->client_name ?? '—' }}</td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $sale->product?->product_name ?? '—' }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex rounded-full bg-brand-soft px-2.5 py-0.5 text-[11px] font-medium text-brand">
                                        {{ $sale->product?->site?->site_name ?? '—' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-right font-semibold text-slate-900">
                                    {{ number_format((float) $sale->total_price, 0) }} RWF
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('admin.sales.show', $sale) }}" class="text-xs font-medium text-brand hover:underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-400">No sales in this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
