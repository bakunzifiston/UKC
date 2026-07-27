<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Hydroponics;
use App\Models\Product;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $siteId = '';

    public string $hydroponicsId = '';

    public string $stockFilter = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingSiteId(): void
    {
        $this->resetPage();
    }

    public function updatingHydroponicsId(): void
    {
        $this->resetPage();
    }

    public function updatingStockFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'Product deleted.');
    }

    public function render()
    {
        $base = Product::query()
            ->when($this->siteId !== '', fn ($q) => $q->where('site_id', $this->siteId))
            ->when($this->hydroponicsId !== '', fn ($q) => $q->where('hydroponics_id', $this->hydroponicsId))
            ->when($this->stockFilter === 'low', fn ($q) => $q->where('quantity', '<=', 10))
            ->when($this->stockFilter === 'ok', fn ($q) => $q->where('quantity', '>', 10));

        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $lowStock = (clone $base)->where('quantity', '<=', 10)->count();
        $inventoryValue = (float) (clone $base)->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as value')->value('value');
        $totalQty = (int) (clone $base)->sum('quantity');

        $products = (clone $base)
            ->with(['site', 'hydroponics'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('product_name', 'like', '%'.$this->search.'%')
                        ->orWhere('product_type', 'like', '%'.$this->search.'%')
                        ->orWhereHas('site', fn ($s) => $s->where('site_name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('hydroponics', fn ($h) => $h->where('hydroponics_name', 'like', '%'.$this->search.'%'));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.products-table', [
            'products' => $products,
            'sites' => Site::orderBy('site_name')->get(['id', 'site_name']),
            'categories' => Hydroponics::orderBy('hydroponics_name')->get(['id', 'hydroponics_name']),
            'kpis' => [
                ['label' => 'Products', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'cube', 'tone' => 'emerald'],
                ['label' => 'Low stock', 'value' => number_format($lowStock), 'description' => 'Quantity ≤ 10 kg', 'trend' => null, 'positive' => $lowStock === 0, 'icon' => 'warning', 'tone' => 'rose'],
                ['label' => 'Total qty', 'value' => number_format($totalQty).' kg', 'description' => 'Sum of quantity', 'trend' => null, 'positive' => true, 'icon' => 'scale', 'tone' => 'blue'],
                ['label' => 'Inventory value', 'value' => number_format($inventoryValue, 0).' RWF', 'description' => 'qty × unit_price', 'trend' => null, 'positive' => true, 'icon' => 'currency', 'tone' => 'amber'],
            ],
        ]);
    }
}
