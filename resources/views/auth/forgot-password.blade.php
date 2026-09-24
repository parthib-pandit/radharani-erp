<x-layouts.guest title="Forgot password · Radharani Jewellery Works">
<x-auth.shell audience="staff" heading="A new password in a minute." lede="We'll email a reset link to the address on your staff account. It works once and expires after an hour.">
    <x-slot:aside>
        <a href="{{ route('login') }}" class="group inline-flex items-center gap-1.5 text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">
            <x-ui.icon name="arrow-left" :size="14" class="transition-transform group-hover:-translate-x-0.5" /> Back to sign in
        </a>
    </x-slot:aside>

    <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">Password help</p>
    <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Forgot your password?</h1>
    <p class="text-[14px] text-ink_text-secondary mt-2">Enter your work email and we'll send you a link to choose a new one.</p>

    @if (session('status'))
        <div class="flex items-start gap-2.5 mt-6 px-4 py-3 rounded-xl bg-success-bg text-success text-[13px] font-medium">
            <x-ui.icon name="check-circle" :size="16" class="shrink-0 mt-px" /> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5" x-data="{ submitting: false }" x-on:submit="submitting = true">
        @csrf
        <div>
            <label for="email" class="rj-label">Work email</label>
            <div class="rj-input-icon">
                <x-ui.icon name="mail" :size="16" />
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@radharani.in"
                    class="rj-input h-12 pl-10 text-[14px] @error('email') is-invalid @enderror">
            </div>
            @error('email')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>
        <x-auth.submit busy="Sending link">Email me a reset link</x-auth.submit>
    </form>

    <p class="text-[12.5px] text-ink_text-muted mt-6">Only have a mobile number on your account? Ask the shop owner or manager to reset it for you.</p>
</x-auth.shell>
</x-layouts.guest>
