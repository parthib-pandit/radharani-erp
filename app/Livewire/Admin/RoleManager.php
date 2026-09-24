<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManager extends Component
{
    public ?int $editingId = null;
    public string $name = '';
    public array $selectedPermissions = [];
    public bool $showNewRoleForm = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:50|unique:roles,name' . ($this->editingId ? ',' . $this->editingId : ''),
            'selectedPermissions' => 'array',
        ];
    }

    public function newRole()
    {
        $this->reset(['editingId', 'name', 'selectedPermissions']);
        $this->showNewRoleForm = true;
    }

    public function edit(int $id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->showNewRoleForm = true;
    }

    public function save()
    {
        $this->validate();

        $role = Role::updateOrCreate(['id' => $this->editingId], ['name' => $this->name]);
        $role->syncPermissions($this->selectedPermissions);

        $this->showNewRoleForm = false;
        $this->reset(['editingId', 'name', 'selectedPermissions']);
        session()->flash('message', 'Role saved.');
    }

    public function cancel()
    {
        $this->showNewRoleForm = false;
        $this->reset(['editingId', 'name', 'selectedPermissions']);
    }

    // Roles with users attached should not be deletable from here without
    // reassignment first — enforce that check before allowing delete.
    public function delete(int $id)
    {
        $role = Role::findOrFail($id);
        if ($role->users()->count() > 0) {
            session()->flash('error', 'Cannot delete a role with users assigned. Reassign them first.');
            return;
        }
        $role->delete();
        session()->flash('message', 'Role deleted.');
    }

    public function render()
    {
        return view('livewire.admin.role-manager', [
            'roles' => Role::withCount('users', 'permissions')->orderBy('name')->get(),
            'allPermissions' => Permission::orderBy('name')->pluck('name'),
        ])->layout('components.layouts.app', ['title' => 'Roles & Permissions — Radharani Jewellery']);
    }
}
