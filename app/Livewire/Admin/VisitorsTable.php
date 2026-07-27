<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Visitor;
use Livewire\Component;
use Livewire\WithPagination;

class VisitorsTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $emailFilter = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingEmailFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Visitor::findOrFail($id)->delete();
        session()->flash('success', 'Visitor deleted.');
    }

    public function render()
    {
        $base = Visitor::query()
            ->when($this->emailFilter === 'with', fn ($q) => $q->whereNotNull('email')->where('email', '!=', ''))
            ->when($this->emailFilter === 'without', fn ($q) => $q->where(fn ($qq) => $qq->whereNull('email')->orWhere('email', '')));

        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $withEmail = (clone $base)->whereNotNull('email')->where('email', '!=', '')->count();
        $uniqueEmails = (int) (clone $base)
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->selectRaw('COUNT(DISTINCT email) as aggregate')
            ->value('aggregate');

        $visitors = (clone $base)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.visitors-table', [
            'visitors' => $visitors,
            'kpis' => [
                ['label' => 'Visitors', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'eye', 'tone' => 'emerald'],
                ['label' => 'With email', 'value' => number_format($withEmail), 'description' => 'Contactable visitors', 'trend' => null, 'positive' => true, 'icon' => 'mail', 'tone' => 'blue'],
                ['label' => 'Unique emails', 'value' => number_format($uniqueEmails), 'description' => 'Distinct email addresses', 'trend' => null, 'positive' => true, 'icon' => 'users', 'tone' => 'violet'],
            ],
        ]);
    }
}
