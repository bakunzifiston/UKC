<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Hydroponics;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class HydroponicsTable extends Component
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
        Hydroponics::findOrFail($id)->delete();
        session()->flash('success', 'Category deleted.');
    }

    public function render()
    {
        $base = Hydroponics::query()
            ->when($this->siteId !== '', fn ($q) => $q->where('site_id', $this->siteId));

        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $sitesCovered = (clone $base)->distinct('site_id')->count('site_id');
        $withDescription = (clone $base)->whereNotNull('description')->where('description', '!=', '')->count();

        $items = (clone $base)
            ->with('site')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('hydroponics_name', 'like', '%'.$this->search.'%')
                        ->orWhereHas('site', fn ($s) => $s->where('site_name', 'like', '%'.$this->search.'%'));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.hydroponics-table', [
            'items' => $items,
            'sites' => Site::orderBy('site_name')->get(['id', 'site_name']),
            'kpis' => [
                ['label' => 'Categories', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'leaf', 'tone' => 'emerald'],
                ['label' => 'Sites covered', 'value' => number_format($sitesCovered), 'description' => 'Distinct sites', 'trend' => null, 'positive' => true, 'icon' => 'building', 'tone' => 'blue'],
                ['label' => 'With description', 'value' => number_format($withDescription), 'description' => 'Documented categories', 'trend' => null, 'positive' => true, 'icon' => 'clipboard', 'tone' => 'amber'],
            ],
        ]);
    }
}
