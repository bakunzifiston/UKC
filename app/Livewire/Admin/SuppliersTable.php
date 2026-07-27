<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\ProductSupplier;
use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;

class SuppliersTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function delete(int $id): void
    {
        Supplier::findOrFail($id)->delete();
        session()->flash('success', 'Supplier deleted.');
    }

    public function render()
    {
        $base = Supplier::query();
        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $withAddress = (clone $base)->whereNotNull('address')->where('address', '!=', '')->count();
        $linked = ProductSupplier::query()
            ->whereIn('supplier_id', (clone $base)->select('id'))
            ->distinct()
            ->count('supplier_id');

        $suppliers = (clone $base)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('supplier_name', 'like', '%'.$this->search.'%')
                        ->orWhere('contact_info', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.suppliers-table', [
            'suppliers' => $suppliers,
            'kpis' => [
                ['label' => 'Suppliers', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'truck', 'tone' => 'emerald'],
                ['label' => 'With address', 'value' => number_format($withAddress), 'description' => 'Address filled', 'trend' => null, 'positive' => true, 'icon' => 'map', 'tone' => 'blue'],
                ['label' => 'Linked to products', 'value' => number_format($linked), 'description' => 'Via product_suppliers', 'trend' => null, 'positive' => true, 'icon' => 'cube', 'tone' => 'amber'],
            ],
        ]);
    }
}
