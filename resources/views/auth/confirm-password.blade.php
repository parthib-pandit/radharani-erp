<x-layouts.guest title="Confirm your password · Radharani Jewellery Works">
<x-auth.shell audience="staff" heading="One more check." lede="Some screens change money, stock or staff access, so we ask for your password again before opening them.">
    <x-slot:aside>
        <a href="{{ url()->previous() }}" class="group inline-flex items-center gap-1.5 text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">
            <x-ui.icon name="arrow-left" :size="14" class="transition-transform group-hover:-translate-x-0.5" /> Go back
        </a>
    </x-slot:aside>

    <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">Secure area</p>
    <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Confirm it's you</h1>
    <p class="text-[14px] text-ink_text-secondary mt-2">Signed in as <span class="font-semibold text-ink_text-primary">{{ auth()->user()->name }}</span>. Enter your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-5" x-data="{ submitting: false }" x-on:submit="submitting = true">
        @csrf
        <div>
            <label for="password" class="rj-label">Password</label>
            <x-ui.password-input id="password" name="password" required autofocus autocomplete="current-password" :invalid="$errors->has('password')" />
            @error('password')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>
        <x-auth.submit busy="Checking">Continue</x-auth.submit>
    </form>
</x-auth.shell>
</x-layouts.guest>
