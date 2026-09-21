<?php
namespace App\Livewire\Portal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CustomerLogin extends Component
{
    public string $phone = '';
    public string $password = '';

    public function login()
    {
        $this->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::guard('customer')->attempt(['phone' => $this->phone, 'password' => $this->password])) {
            throw ValidationException::withMessages(['phone' => 'Invalid phone number or password.']);
        }

        session()->regenerate();
        $this->redirect(route('portal.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.portal.customer-login')
            ->layout('components.layouts.guest');
    }
}