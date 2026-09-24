<x-layouts.guest title="Staff sign in · Radharani Jewellery Works">
<x-auth.shell audience="staff">
    <x-slot:aside>
        <a href="{{ route('portal.login') }}" class="group inline-flex items-center gap-1.5 text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">
            Customer? Sign in to the portal
            <x-ui.icon name="arrow-right" :size="14" class="transition-transform group-hover:translate-x-0.5" />
        </a>
    </x-slot:aside>

    {{-- Mobile number is the default: staff know their phone number better than a work email. --}}
    <div x-data="{ method: '{{ old('method', 'phone') }}', submitting: false }">
        <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">Staff sign in</p>
        <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Welcome back</h1>
        <p class="text-[14px] text-ink_text-secondary mt-2">Sign in with your mobile number or work email.</p>

        @session('status')
            <div class="flex items-start gap-2.5 mt-6 px-4 py-3 rounded-xl bg-success-bg text-success text-[13px] font-medium">
                <x-ui.icon name="check-circle" :size="16" class="shrink-0 mt-px" /> {{ $value }}
            </div>
        @endsession

        <div class="rj-segment w-full mt-7" role="tablist" aria-label="Sign in with">
            <button type="button" role="tab" x-on:click="method = 'phone'; $nextTick(() => $refs.phone.focus())" :aria-selected="method === 'phone'"
                :class="method === 'phone' ? 'is-active' : ''" class="flex-1 justify-center h-9">
                <x-ui.icon name="smartphone" :size="15" /> Mobile number
            </button>
            <button type="button" role="tab" x-on:click="method = 'email'; $nextTick(() => $refs.email.focus())" :aria-selected="method === 'email'"
                :class="method === 'email' ? 'is-active' : ''" class="flex-1 justify-center h-9">
                <x-ui.icon name="mail" :size="15" /> Email
            </button>
        </div>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5" x-on:submit="submitting = true">
            @csrf
            <input type="hidden" name="method" x-bind:value="method">

            <div x-show="method === 'phone'">
                <label for="phone" class="rj-label">Mobile number</label>
                <div class="relative flex">
                    <span class="inline-flex items-center gap-1.5 h-12 pl-3.5 pr-3 rounded-l-control border border-r-0 border-line bg-surface-sunken text-[14px] font-semibold text-ink_text-secondary">+91</span>
                    <input id="phone" x-ref="phone" type="tel" inputmode="numeric" maxlength="16"
                        x-bind:name="method === 'phone' ? 'login' : null" x-bind:required="method === 'phone'" x-bind:disabled="method !== 'phone'"
                        value="{{ old('method', 'phone') === 'phone' ? old('login') : '' }}"
                        @if (old('method', 'phone') === 'phone') autofocus @endif autocomplete="username" placeholder="98300 00000"
                        class="rj-input h-12 rounded-l-none text-[15px] tracking-wide tabular @error('login') is-invalid @enderror">
                </div>
            </div>

            <div x-show="method === 'email'" x-cloak>
                <label for="email" class="rj-label">Work email</label>
                <div class="rj-input-icon">
                    <x-ui.icon name="mail" :size="16" />
                    <input id="email" x-ref="email" type="email"
                        x-bind:name="method === 'email' ? 'login' : null" x-bind:required="method === 'email'" x-bind:disabled="method !== 'email'"
                        value="{{ old('method') === 'email' ? old('login') : '' }}"
                        @if (old('method') === 'email') autofocus @endif autocomplete="username" placeholder="you@radharani.in"
                        class="rj-input h-12 pl-10 text-[14px] @error('login') is-invalid @enderror">
                </div>
            </div>

            @error('login')
                <p class="rj-error -mt-3"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="rj-label !mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[12.5px] font-semibold">Forgot password?</a>
                    @endif
                </div>
                <x-ui.password-input id="password" name="password" required autocomplete="current-password" placeholder="Your password" :invalid="$errors->has('password')" />
                @error('password')
                    <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex items-center gap-2.5 text-[13px] text-ink_text-secondary cursor-pointer select-none">
                <input type="checkbox" name="remember" class="rj-checkbox" @checked(old('remember'))>
                Keep me signed in on this device
            </label>

            <button type="submit" :disabled="submitting"
                class="press w-full h-12 rounded-control gold-sheen text-white text-[14.5px] font-bold shadow-gold border border-gold-dark/30 hover:brightness-[1.07] disabled:opacity-80 inline-flex items-center justify-center gap-2">
                <span x-show="!submitting" class="inline-flex items-center gap-2">Sign in <x-ui.icon name="arrow-right" :size="16" /></span>
                <span x-show="submitting" x-cloak class="inline-flex items-center gap-2"><x-ui.icon name="loader" :size="16" class="animate-spin" /> Signing in</span>
            </button>
        </form>

        <div class="flex items-start gap-3 mt-8 p-4 rounded-xl bg-white ring-1 ring-line-light">
            <x-ui.icon name="shield-check" :size="17" class="text-gold-dark shrink-0 mt-0.5" />
            <p class="text-[12.5px] text-ink_text-secondary leading-relaxed">Access is limited to your role. Trouble signing in? Ask the shop owner or manager to reset your account.</p>
        </div>
    </div>
</x-auth.shell>
</x-layouts.guest>
