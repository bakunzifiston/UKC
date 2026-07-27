<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Client;
use App\Models\Sale;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class SalesTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $clientId = '';

    public string $siteId = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingClientId(): void
    {
        $this->resetPage();
    }

    public function updatingSiteId(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->preset = 'custom';
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->preset = 'custom';
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Sale::findOrFail($id)->delete();
        session()->flash('success', 'Sale deleted.');
    }

    public function render()
    {
        $base = Sale::query()
            ->when($this->clientId !== '', fn ($q) => $q->where('client_id', $this->clientId))
            ->when($this->siteId !== '', fn ($q) => $q->whereHas('product', fn ($p) => $p->where('site_id', $this->siteId)));

        $this->applyDateFilter($base, 'sale_date');

        $filtered = (clone $base);
        $count = (clone $filtered)->count();
        $revenue = (float) (clone $filtered)->sum('total_price');
        $qty = (int) (clone $filtered)->sum('quantity_sold');
        $avg = $count > 0 ? $revenue / $count : 0.0;

        [$prevFrom, $prevTo] = $this->previousPeriodBounds();
        $prevRevenue = 0.0;
        $comparable = $prevFrom && $prevTo;
        if ($comparable) {
            $prevRevenue = (float) Sale::query()
                ->when($this->clientId !== '', fn ($q) => $q->where('client_id', $this->clientId))
                ->when($this->siteId !== '', fn ($q) => $q->whereHas('product', fn ($p) => $p->where('site_id', $this->siteId)))
                ->whereDate('sale_date', '>=', $prevFrom)
                ->whereDate('sale_date', '<=', $prevTo)
                ->sum('total_price');
        }
        $trend = $this->formatTrend($revenue, $prevRevenue, (bool) $comparable);

        $sales = (clone $base)
            ->with(['product.site', 'client'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('sale_date', 'like', '%'.$this->search.'%')
                        ->orWhereHas('product', fn ($p) => $p->where('product_name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('client', fn ($c) => $c->where('client_name', 'like', '%'.$this->search.'%'));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.sales-table', [
            'sales' => $sales,
            'clients' => Client::orderBy('client_name')->get(['id', 'client_name']),
            'sites' => Site::orderBy('site_name')->get(['id', 'site_name']),
            'kpis' => [
                [
                    'label' => 'Sales',
                    'value' => number_format($count),
                    'description' => 'In filtered set',
                    'trend' => null,
                    'positive' => true,
                    'icon' => 'cart',
                    'tone' => 'emerald',
                ],
                [
                    'label' => 'Revenue',
                    'value' => number_format($revenue, 0).' RWF',
                    'description' => 'Sum of total_price',
                    'trend' => $trend['trend'],
                    'positive' => $trend['positive'],
                    'icon' => 'currency',
                    'tone' => 'blue',
                ],
                [
                    'label' => 'Avg sale',
                    'value' => number_format($avg, 0).' RWF',
                    'description' => number_format($qty).' kg sold',
                    'trend' => null,
                    'positive' => true,
                    'icon' => 'chart',
                    'tone' => 'amber',
                ],
            ],
        ]);
    }
}
