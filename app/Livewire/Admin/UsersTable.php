<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $verifiedFilter = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingVerifiedFilter(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'User deleted.');
    }

    public function render()
    {
        $base = User::query()
            ->when($this->verifiedFilter === 'yes', fn ($q) => $q->whereNotNull('email_verified_at'))
            ->when($this->verifiedFilter === 'no', fn ($q) => $q->whereNull('email_verified_at'));

        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $verified = (clone $base)->whereNotNull('email_verified_at')->count();
        $unverified = (clone $base)->whereNull('email_verified_at')->count();

        $users = (clone $base)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.users-table', [
            'users' => $users,
            'kpis' => [
                ['label' => 'Users', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'users', 'tone' => 'emerald'],
                ['label' => 'Verified', 'value' => number_format($verified), 'description' => 'email_verified_at set', 'trend' => null, 'positive' => true, 'icon' => 'check', 'tone' => 'blue'],
                ['label' => 'Unverified', 'value' => number_format($unverified), 'description' => 'Pending verification', 'trend' => null, 'positive' => $unverified === 0, 'icon' => 'warning', 'tone' => 'rose'],
            ],
        ]);
    }
}
