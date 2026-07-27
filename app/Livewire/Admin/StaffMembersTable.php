<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\StaffMember;
use Livewire\Component;
use Livewire\WithPagination;

class StaffMembersTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $role = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        StaffMember::findOrFail($id)->delete();
        session()->flash('success', 'Staff member deleted.');
    }

    public function render()
    {
        $base = StaffMember::query()
            ->when($this->role !== '', fn ($q) => $q->where('role', $this->role));

        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $roles = (clone $base)->distinct('role')->count('role');
        $sites = (clone $base)->distinct('site_name')->count('site_name');
        $withEmail = (clone $base)->whereNotNull('email')->where('email', '!=', '')->count();

        $staffMembers = (clone $base)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('role', 'like', '%'.$this->search.'%')
                        ->orWhere('site_name', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.staff-members-table', [
            'staffMembers' => $staffMembers,
            'roleOptions' => StaffMember::query()->select('role')->distinct()->orderBy('role')->pluck('role'),
            'kpis' => [
                ['label' => 'Staff', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'users', 'tone' => 'emerald'],
                ['label' => 'Roles', 'value' => number_format($roles), 'description' => 'Distinct roles', 'trend' => null, 'positive' => true, 'icon' => 'badge', 'tone' => 'blue'],
                ['label' => 'Site names', 'value' => number_format($sites), 'description' => 'Distinct site_name values', 'trend' => null, 'positive' => true, 'icon' => 'building', 'tone' => 'amber'],
                ['label' => 'With email', 'value' => number_format($withEmail), 'description' => 'Contactable staff', 'trend' => null, 'positive' => true, 'icon' => 'mail', 'tone' => 'violet'],
            ],
        ]);
    }
}
