<?php
namespace App\Livewire\Admin;

use App\Models\Customer\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CustomerManager extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;

    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $email = '';
    public string $gstin = '';
    public string $status = 'past_customer';

    public bool $showPasswordFor = false;
    public ?int $passwordCustomerId = null;
    public string $newPassword = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:15|unique:customers,phone' . ($this->editingId ? ',' . $this->editingId : ''),
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:100',
            'gstin' => 'nullable|string|max:15',
            'status' => 'required|in:past_customer,order_given,order_pending',
        ];
    }

    public function updatingSearch() { $this->resetPage(); }

    public function edit(int $id)
    {
        $c = Customer::findOrFail($id);
        $this->editingId = $c->id;
        $this->name = $c->name;
        $this->phone = $c->phone;
        $this->address = (string) $c->address;
        $this->email = (string) $c->email;
        $this->gstin = (string) $c->gstin;
        $this->status = $c->status;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->email,
            'gstin' => $this->gstin,
            'status' => $this->status,
        ];

        // New customers get a referral code immediately — this is what
        // makes them shareable from day one, not just after their first sale.
        if (! $this->editingId) {
            do {
                $code = strtoupper(Str::random(6));
            } while (Customer::where('referral_code', $code)->exists());
            $data['referral_code'] = $code;
        }

        Customer::updateOrCreate(['id' => $this->editingId], $data);

        $this->cancel();
        session()->flash('message', 'Customer saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'name', 'phone', 'address', 'email', 'gstin']);
        $this->status = 'past_customer';
    }

    // Staff-assigned only — there is no customer self-signup flow.
    // This is the counter-side half of "ask at the counter to set up
    // portal access" shown on the portal login screen.
    public function openPasswordForm(int $id)
    {
        $this->passwordCustomerId = $id;
        $this->newPassword = '';
        $this->showPasswordFor = true;
    }

    public function setPassword()
    {
        $this->validate(['newPassword' => 'required|min:8']);

        Customer::whereKey($this->passwordCustomerId)->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->showPasswordFor = false;
        session()->flash('message', 'Portal password set. Share it with the customer directly.');
    }

    public function render()
    {
        return view('livewire.admin.customer-manager', [
            'customers' => Customer::where('name', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%")
                ->orderByDesc('id')
                ->paginate(15),
        ])->layout('components.layouts.app', ['title' => 'Customers — Radharani Jewellery']);
    }
}
