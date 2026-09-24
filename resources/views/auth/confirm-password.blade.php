<x-layouts.guest title="Confirm Password — Radharani Jewellery ERP">
<div class="min-h-screen bg-surface-bg flex items-center justify-center p-10">
    <x-ui.card class="w-full max-w-[400px]">
        <div class="text-xl font-semibold mb-1">Confirm your password</div>
        <div class="text-[13px] text-ink_text-secondary mb-6">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />

                <x-text-input id="password" class="mt-1"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-5">
                <x-primary-button class="w-full">
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </x-ui.card>
</div>
</x-layouts.guest>
