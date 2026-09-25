<x-layouts.guest title="Create staff account · Radharani Jewellery Works">
<x-auth.shell audience="staff" heading="Welcome to the workshop." lede="Create your staff login. The shop owner assigns your role before you can see stock or sales.">
    <x-slot:aside>
        <a href="{{ route('login') }}" class="group inline-flex items-center gap-1.5 text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">
            Already have an account? Sign in
            <x-ui.icon name="arrow-right" :size="14" class="transition-transform group-hover:translate-x-0.5" />
        </a>
    </x-slot:aside>

    <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">Staff account</p>
    <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Create your account</h1>
    <p class="text-[14px] text-ink_text-secondary mt-2">Use your own details. Every action you take is recorded against this account.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5" x-data="{ submitting: false }" x-on:submit="submitting = true">
        @csrf
        <div>
            <label for="name" class="rj-label">Full name</label>
            <div class="rj-input-icon">
                <x-ui.icon name="user" :size="16" />
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="rj-input h-12 pl-10 text-[14px] @error('name') is-invalid @enderror">
            </div>
            @error('name')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="rj-label">Work email</label>
            <div class="rj-input-icon">
                <x-ui.icon name="mail" :size="16" />
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="rj-input h-12 pl-10 text-[14px] @error('email') is-invalid @enderror">
            </div>
            @error('email')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="rj-label">Password</label>
                <x-ui.password-input id="password" name="password" required autocomplete="new-password" :invalid="$errors->has('password')" />
            </div>
            <div>
                <label for="password_confirmation" class="rj-label">Type it again</label>
                <x-ui.password-input id="password_confirmation" name="password_confirmation" required autocomplete="new-password" />
            </div>
        </div>
        @error('password')
            <p class="rj-error -mt-3"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
        @enderror

        <x-auth.submit busy="Creating account">Create account</x-auth.submit>
    </form>
</x-auth.shell>
</x-layouts.guest>
