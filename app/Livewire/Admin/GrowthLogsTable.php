<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\GrowthLog;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class GrowthLogsTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $siteId = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingSiteId(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        GrowthLog::findOrFail($id)->delete();
        session()->flash('success', 'Growth log deleted.');
    }

    public function render()
    {
        $base = GrowthLog::query()
            ->when($this->siteId !== '', fn ($q) => $q->where('site_id', $this->siteId));

        $this->applyDateFilter($base, 'log_date');

        $total = (clone $base)->count();
        $avgTrays = (float) ((clone $base)->avg('growth_value') ?? 0);
        $productsLogged = (clone $base)->distinct('product_id')->count('product_id');
        $sitesLogged = (clone $base)->distinct('site_id')->count('site_id');

        $logs = (clone $base)
            ->with(['site', 'hydroponics', 'product'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('site', fn ($s) => $s->where('site_name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('hydroponics', fn ($h) => $h->where('hydroponics_name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('product', fn ($p) => $p->where('product_name', 'like', '%'.$this->search.'%'));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.growth-logs-table', [
            'logs' => $logs,
            'sites' => Site::orderBy('site_name')->get(['id', 'site_name']),
            'kpis' => [
                ['label' => 'Logs', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'clipboard', 'tone' => 'emerald'],
                ['label' => 'Avg trays', 'value' => number_format($avgTrays, 1), 'description' => 'Average growth_value', 'trend' => null, 'positive' => true, 'icon' => 'chart', 'tone' => 'blue'],
                ['label' => 'Products logged', 'value' => number_format($productsLogged), 'description' => 'Distinct products', 'trend' => null, 'positive' => true, 'icon' => 'cube', 'tone' => 'amber'],
                ['label' => 'Sites logged', 'value' => number_format($sitesLogged), 'description' => 'Distinct sites', 'trend' => null, 'positive' => true, 'icon' => 'building', 'tone' => 'violet'],
            ],
        ]);
    }
}
