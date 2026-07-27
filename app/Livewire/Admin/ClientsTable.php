<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Client;
use App\Models\Sale;
use Livewire\Component;
use Livewire\WithPagination;

class ClientsTable extends Component
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
        Client::findOrFail($id)->delete();
        session()->flash('success', 'Client deleted.');
    }

    public function render()
    {
        $base = Client::query();
        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $withAddress = (clone $base)->whereNotNull('address')->where('address', '!=', '')->count();
        $withSales = Sale::query()
            ->whereIn('client_id', (clone $base)->select('id'))
            ->distinct()
            ->count('client_id');

        $clients = (clone $base)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('client_name', 'like', '%'.$this->search.'%')
                        ->orWhere('contact_info', 'like', '%'.$this->search.'%')
                        ->orWhere('address', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.clients-table', [
            'clients' => $clients,
            'kpis' => [
                ['label' => 'Clients', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'users', 'tone' => 'emerald'],
                ['label' => 'With address', 'value' => number_format($withAddress), 'description' => 'Address filled', 'trend' => null, 'positive' => true, 'icon' => 'map', 'tone' => 'blue'],
                ['label' => 'With sales', 'value' => number_format($withSales), 'description' => 'Clients who purchased', 'trend' => null, 'positive' => true, 'icon' => 'cart', 'tone' => 'amber'],
            ],
        ]);
    }
}
