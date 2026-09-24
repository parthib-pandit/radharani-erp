<?php
namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?int $employee_id = null;
    public array $selectedRoles = [];
    public bool $is_active = true;

    protected function rules(): array
    {
        $emailRule = 'required|email|max:100|unique:users,email' . ($this->editingId ? ',' . $this->editingId : '');

        return [
            'name' => 'required|string|max:100',
            'email' => $emailRule,
            'password' => $this->editingId ? 'nullable|min:8' : 'required|min:8',
            'employee_id' => 'nullable|exists:employees,id',
            'selectedRoles' => 'required|array|min:1',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }

    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->employee_id = $user->employee_id;
        $this->is_active = $user->is_active;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'employee_id' => $this->employee_id,
            'is_active' => $this->is_active,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->editingId], $data);
        $user->syncRoles($this->selectedRoles);

        $this->cancel();
        session()->flash('message', 'User saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'name', 'email', 'password', 'employee_id', 'selectedRoles']);
        $this->is_active = true;
    }

    // Never delete a user — every movement/sale references user_id.
    // Deactivation blocks login without breaking audit history.
    public function toggleActive(int $id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.user-manager', [
            'users' => User::with('roles', 'employee')
                ->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orderByDesc('id')
                ->paginate(15),
            'roles' => Role::orderBy('name')->pluck('name'),
            'employees' => Employee::where('status', 'active')->orderBy('name')->get(),
        ])->layout('components.layouts.app', ['title' => 'Users — Radharani Jewellery']);
    }
}
