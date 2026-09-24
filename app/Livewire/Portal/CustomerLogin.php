<?php
namespace App\Livewire\Portal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use App\Support\Phone;

class CustomerLogin extends Component
{
    public string $phone = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $key = 'portal-login|' . Phone::normalize($this->phone) . '|' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['phone' => 'Too many attempts. Try again in ' . RateLimiter::availableIn($key) . ' seconds.']);
        }

        if (! Auth::guard('customer')->attempt(['phone' => Phone::normalize($this->phone), 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key);
            throw ValidationException::withMessages(['phone' => "That phone number and password don't match an account."]);
        }
        RateLimiter::clear($key);

        session()->regenerate();
        $this->redirect(route('portal.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.portal.customer-login')
            ->layout('components.layouts.guest', ['title' => 'Customer sign in · Radharani Jewellery Works']);
    }
}