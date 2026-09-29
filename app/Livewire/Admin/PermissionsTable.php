<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Concerns\WithSortingSearch;
use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

class PermissionsTable extends Component
{
    use WithPagination;
    use WithSortingSearch;

    public string $module = '';

    public function updatingModule(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        abort_unless(auth()->user()->can('permissions.delete'), 403);

        $permission = Permission::query()->findOrFail($id);

        if ($permission->is_system) {
            $this->addError('delete', 'System permissions cannot be deleted.');

            return;
        }

        if ($permission->roles()->exists()) {
            $this->addError('delete', 'Remove this permission from its roles before deleting it.');

            return;
        }

        $permission->delete();
        session()->flash('success', 'Permission deleted.');
    }

    public function render()
    {
        $permissions = Permission::query()
            ->withCount('roles')
            ->when($this->module !== '', fn ($query) => $query->where('module', $this->module))
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('slug', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy($this->sortField === 'id' ? 'module' : $this->sortField, $this->sortField === 'id' ? 'asc' : $this->sortDirection)
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.permissions-table', [
            'permissions' => $permissions,
            'moduleOptions' => Permission::query()->select('module')->distinct()->orderBy('module')->pluck('module'),
            'kpis' => [
                ['label' => 'Permissions', 'value' => number_format(Permission::query()->count()), 'description' => 'Assignable permissions', 'trend' => null, 'positive' => true, 'icon' => 'badge', 'tone' => 'emerald'],
                ['label' => 'Modules', 'value' => number_format(Permission::query()->distinct('module')->count('module')), 'description' => 'Grouped areas', 'trend' => null, 'positive' => true, 'icon' => 'chart', 'tone' => 'blue'],
                ['label' => 'Custom', 'value' => number_format(Permission::query()->where('is_system', false)->count()), 'description' => 'Added beyond the catalog', 'trend' => null, 'positive' => true, 'icon' => 'check', 'tone' => 'amber'],
            ],
        ]);
    }
}
