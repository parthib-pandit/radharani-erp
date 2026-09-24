<x-layouts.guest title="Forgot Password — Radharani Jewellery ERP">
<div class="min-h-screen bg-surface-bg flex items-center justify-center p-10">
    <x-ui.card class="w-full max-w-[400px]">
        <div class="text-xl font-semibold mb-1">Forgot your password?</div>
        <div class="text-[13px] text-ink_text-secondary mb-6">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-5">
                <x-primary-button class="w-full">
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </x-ui.card>
</div>
</x-layouts.guest>
