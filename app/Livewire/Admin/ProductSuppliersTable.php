<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class ProductSuppliersTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $supplierId = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingSupplierId(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        ProductSupplier::findOrFail($id)->delete();
        session()->flash('success', 'Product supplier deleted.');
    }

    public function render()
    {
        $base = ProductSupplier::query()
            ->when($this->supplierId !== '', fn ($q) => $q->where('supplier_id', $this->supplierId));

        $this->applyDateFilter($base, 'supplied_date');

        $total = (clone $base)->count();
        $qty = (int) (clone $base)->sum('supplied_quantity');
        $suppliers = (clone $base)->distinct('supplier_id')->count('supplier_id');
        $products = (clone $base)->distinct('product_id')->count('product_id');

        $items = (clone $base)
            ->with(['product', 'supplier'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('product', fn ($p) => $p->where('product_name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('supplier', fn ($s) => $s->where('supplier_name', 'like', '%'.$this->search.'%'));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.product-suppliers-table', [
            'items' => $items,
            'supplierOptions' => Supplier::orderBy('supplier_name')->get(['id', 'supplier_name']),
            'kpis' => [
                ['label' => 'Supply records', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'clipboard', 'tone' => 'emerald'],
                ['label' => 'Qty supplied', 'value' => number_format($qty).' kg', 'description' => 'Sum of supplied_quantity', 'trend' => null, 'positive' => true, 'icon' => 'scale', 'tone' => 'blue'],
                ['label' => 'Suppliers', 'value' => number_format($suppliers), 'description' => 'Distinct suppliers', 'trend' => null, 'positive' => true, 'icon' => 'truck', 'tone' => 'amber'],
                ['label' => 'Products', 'value' => number_format($products), 'description' => 'Distinct products', 'trend' => null, 'positive' => true, 'icon' => 'cube', 'tone' => 'violet'],
            ],
        ]);
    }
}
