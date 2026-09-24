<x-layouts.guest title="Reset Password — Radharani Jewellery ERP">
<div class="min-h-screen bg-surface-bg flex items-center justify-center p-10">
    <x-ui.card class="w-full max-w-[400px]">
        <div class="text-xl font-semibold mb-1">Reset your password</div>
        <div class="text-[13px] text-ink_text-secondary mb-6">Choose a new password for your account.</div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="mt-1" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="mt-1"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-5">
                <x-primary-button class="w-full">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </div>
        </form>
    </x-ui.card>
</div>
</x-layouts.guest>
