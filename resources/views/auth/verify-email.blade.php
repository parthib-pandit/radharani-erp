<x-layouts.guest title="Verify Email — Radharani Jewellery ERP">
<div class="min-h-screen bg-surface-bg flex items-center justify-center p-10">
    <x-ui.card class="w-full max-w-[400px]">
        <div class="text-xl font-semibold mb-1">Verify your email</div>
        <div class="text-[13px] text-ink_text-secondary mb-6">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-4 text-sm">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="text-[12.5px] font-semibold text-gold">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </x-ui.card>
</div>
</x-layouts.guest>
