<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RolesTable extends Component
{
    use WithPagination;
    use WithSortingSearch;

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('roles.delete'), 403);

        $role = Role::query()->findOrFail($id);

        if ($role->is_system) {
            $this->addError('delete', 'This role is required by the system and cannot be deleted.');

            return;
        }

        if ($role->users()->exists()) {
            $this->addError('delete', 'Reassign users before deleting this role.');

            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted.');
    }

    public function render()
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.admin.roles-table', [
            'roles' => $roles,
            'kpis' => [
                ['label' => 'Roles', 'value' => number_format(Role::query()->count()), 'description' => 'Access roles', 'trend' => null, 'positive' => true, 'icon' => 'badge', 'tone' => 'emerald'],
                ['label' => 'System', 'value' => number_format(Role::query()->where('is_system', true)->count()), 'description' => 'Protected roles', 'trend' => null, 'positive' => true, 'icon' => 'check', 'tone' => 'blue'],
                ['label' => 'Custom', 'value' => number_format(Role::query()->where('is_system', false)->count()), 'description' => 'Created by admins', 'trend' => null, 'positive' => true, 'icon' => 'users', 'tone' => 'amber'],
            ],
        ]);
    }
}
