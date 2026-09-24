<x-layouts.guest title="Choose a new password · Radharani Jewellery Works">
<x-auth.shell audience="staff" heading="Almost there." lede="Choose a password you haven't used here before. You'll be signed in with it straight away.">
    <x-slot:aside>
        <a href="{{ route('login') }}" class="group inline-flex items-center gap-1.5 text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">
            <x-ui.icon name="arrow-left" :size="14" class="transition-transform group-hover:-translate-x-0.5" /> Back to sign in
        </a>
    </x-slot:aside>

    <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">Password help</p>
    <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Choose a new password</h1>
    <p class="text-[14px] text-ink_text-secondary mt-2">At least 8 characters. A short phrase is easier to remember than a random word.</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5" x-data="{ submitting: false }" x-on:submit="submitting = true">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="rj-label">Work email</label>
            <div class="rj-input-icon">
                <x-ui.icon name="mail" :size="16" />
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username"
                    class="rj-input h-12 pl-10 text-[14px] @error('email') is-invalid @enderror">
            </div>
            @error('email')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="rj-label">New password</label>
            <x-ui.password-input id="password" name="password" required autofocus autocomplete="new-password" :invalid="$errors->has('password')" />
            @error('password')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="rj-label">Type it again</label>
            <x-ui.password-input id="password_confirmation" name="password_confirmation" required autocomplete="new-password" :invalid="$errors->has('password_confirmation')" />
            @error('password_confirmation')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <x-auth.submit busy="Saving">Save password</x-auth.submit>
    </form>
</x-auth.shell>
</x-layouts.guest>
