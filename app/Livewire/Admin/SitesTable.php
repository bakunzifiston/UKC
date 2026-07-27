<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Product;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class SitesTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $province = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingProvince(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Site::findOrFail($id)->delete();
        session()->flash('success', 'Site deleted.');
    }

    public function render()
    {
        $base = Site::query()
            ->when($this->province !== '', fn ($q) => $q->where('province', $this->province));

        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $provinces = (clone $base)->distinct('province')->count('province');
        $withProducts = Product::query()
            ->whereIn('site_id', (clone $base)->select('id'))
            ->distinct()
            ->count('site_id');

        $sites = (clone $base)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('site_name', 'like', '%'.$this->search.'%')
                        ->orWhere('district', 'like', '%'.$this->search.'%')
                        ->orWhere('sector', 'like', '%'.$this->search.'%')
                        ->orWhere('manager_name', 'like', '%'.$this->search.'%')
                        ->orWhere('contact-details', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.sites-table', [
            'sites' => $sites,
            'provinceOptions' => Site::query()->select('province')->distinct()->orderBy('province')->pluck('province'),
            'kpis' => [
                ['label' => 'Sites', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'building', 'tone' => 'emerald'],
                ['label' => 'Provinces', 'value' => number_format($provinces), 'description' => 'Distinct provinces', 'trend' => null, 'positive' => true, 'icon' => 'map', 'tone' => 'blue'],
                ['label' => 'With products', 'value' => number_format($withProducts), 'description' => 'Sites linked to products', 'trend' => null, 'positive' => true, 'icon' => 'cube', 'tone' => 'amber'],
            ],
        ]);
    }
}
