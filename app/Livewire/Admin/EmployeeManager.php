<?php
namespace App\Livewire\Admin;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeManager extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;

    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $designation = '';
    public ?float $salary = null;
    public string $joining_date = '';
    public string $status = 'active';

    protected $rules = [
        'name' => 'required|string|max:100',
        'phone' => 'nullable|string|max:15',
        'address' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:50',
        'salary' => 'nullable|numeric|min:0',
        'joining_date' => 'nullable|date',
        'status' => 'required|in:active,inactive',
    ];

    public function updatingSearch() { $this->resetPage(); }

    public function edit(int $id)
    {
        $e = Employee::findOrFail($id);
        $this->editingId = $e->id;
        $this->name = $e->name;
        $this->phone = (string) $e->phone;
        $this->address = (string) $e->address;
        $this->designation = (string) $e->designation;
        $this->salary = $e->salary;
        $this->joining_date = optional($e->joining_date)->format('Y-m-d') ?? '';
        $this->status = $e->status;
    }

    public function save()
    {
        $this->validate();

        Employee::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'designation' => $this->designation,
            'salary' => $this->salary,
            'joining_date' => $this->joining_date ?: null,
            'status' => $this->status,
        ]);

        $this->cancel();
        session()->flash('message', 'Employee saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'name', 'phone', 'address', 'designation', 'salary', 'joining_date']);
        $this->status = 'active';
    }

    // Employees are never deleted — only marked inactive. Salary/HR history
    // and any linked user account must stay attributable.
    public function deactivate(int $id)
    {
        Employee::whereKey($id)->update(['status' => 'inactive']);
    }

    public function render()
    {
        return view('livewire.admin.employee-manager', [
            'employees' => Employee::where('name', 'like', "%{$this->search}%")
                ->orWhere('designation', 'like', "%{$this->search}%")
                ->withCount('user')
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('components.layouts.app', ['title' => 'Employees — Radharani Jewellery']);
    }
}
