<?php
namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // #19 Staff login uses phone number or email. The form submits a
    // single `login` field for whichever tab is active, plus `method`
    // ('email' or 'phone') saying which column to check it against.
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'],
            'method' => ['required', 'in:email,phone'],
            'password' => ['required', 'string'],
        ];
    }

    // Overrides Breeze's default authenticate() to add the is_active check
    // and the phone/email identifier switch.
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $field = $this->string('method')->value() === 'phone' ? 'phone' : 'email';

        if (! Auth::attempt([$field => $this->string('login')->value(), 'password' => $this->string('password')->value()], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        if (! Auth::user()->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'login' => 'This account has been disabled. Contact the shop owner.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
