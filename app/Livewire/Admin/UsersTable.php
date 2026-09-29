<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithDateRange;
use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Role;
use App\Models\User;
use App\Support\AccessGuard;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithDateRange;
    use WithPagination;
    use WithSortingSearch;

    public string $verifiedFilter = '';

    public string $role = '';

    public string $status = '';

    public function mount(): void
    {
        $this->mountWithDateRange('all');
    }

    public function updatingVerifiedFilter(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        abort_unless(auth()->user()->can('users.update'), 403);

        $user = User::query()->findOrFail($id);
        AccessGuard::assertCanManage(auth()->user(), $user);

        try {
            AccessGuard::assertSuperAdminRemains($user, null, ! $user->is_active);
        } catch (ValidationException $exception) {
            $this->addError('active', collect($exception->errors())->flatten()->first() ?? 'This account cannot be deactivated.');

            return;
        }

        $user->update(['is_active' => ! $user->is_active]);
        session()->flash('success', $user->is_active ? 'User activated.' : 'User deactivated.');
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('users.delete'), 403);

        $user = User::query()->findOrFail($id);
        $denial = AccessGuard::deleteDenial(auth()->user(), $user);

        if ($denial !== null) {
            $this->addError('delete', $denial);

            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted.');
    }

    public function render()
    {
        $base = User::query()
            ->when($this->verifiedFilter === 'yes', fn ($q) => $q->whereNotNull('email_verified_at'))
            ->when($this->verifiedFilter === 'no', fn ($q) => $q->whereNull('email_verified_at'))
            ->when($this->status === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->when($this->role !== '', fn ($q) => $q->whereHas('roles', fn ($roles) => $roles->where('roles.id', $this->role)));

        $this->applyDateFilter($base, 'created_at');

        $total = (clone $base)->count();
        $active = (clone $base)->where('is_active', true)->count();
        $inactive = (clone $base)->where('is_active', false)->count();

        $users = (clone $base)
            ->with('roles')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhereHas('roles', fn ($roles) => $roles->where('name', 'like', '%'.$this->search.'%'));
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.users-table', [
            'users' => $users,
            'roleOptions' => Role::query()->orderBy('name')->get(['id', 'name']),
            'kpis' => [
                ['label' => 'Users', 'value' => number_format($total), 'description' => 'In filtered set', 'trend' => null, 'positive' => true, 'icon' => 'users', 'tone' => 'emerald'],
                ['label' => 'Active', 'value' => number_format($active), 'description' => 'Can sign in', 'trend' => null, 'positive' => true, 'icon' => 'check', 'tone' => 'blue'],
                ['label' => 'Inactive', 'value' => number_format($inactive), 'description' => 'Deactivated accounts', 'trend' => null, 'positive' => $inactive === 0, 'icon' => 'warning', 'tone' => 'rose'],
            ],
        ]);
    }
}
