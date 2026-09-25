<div>
<x-auth.shell audience="customer">
    <x-slot:aside>
        <a href="{{ route('login') }}" class="group inline-flex items-center gap-1.5 text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">
            Staff sign in
            <x-ui.icon name="arrow-right" :size="14" class="transition-transform group-hover:translate-x-0.5" />
        </a>
    </x-slot:aside>

    <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">Customer portal</p>
    <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Welcome to Radharani</h1>
    <p class="text-[14px] text-ink_text-secondary mt-2">Sign in with the mobile number you gave the shop.</p>

    <form wire:submit="login" class="mt-8 space-y-5">
        <div>
            <label for="portal-phone" class="rj-label">Mobile number</label>
            <div class="relative flex">
                <span class="inline-flex items-center h-12 pl-3.5 pr-3 rounded-l-control border border-r-0 border-line bg-surface-sunken text-[14px] font-semibold text-ink_text-secondary">+91</span>
                <input id="portal-phone" type="tel" inputmode="numeric" maxlength="16" wire:model="phone" autofocus autocomplete="username" placeholder="98300 00000"
                    class="rj-input h-12 rounded-l-none text-[15px] tracking-wide tabular @error('phone') is-invalid @enderror">
            </div>
            @error('phone')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="portal-password" class="rj-label">Password</label>
            <x-ui.password-input id="portal-password" wire:model="password" autocomplete="current-password" placeholder="Your password" :invalid="$errors->has('password')" />
            @error('password')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <label class="inline-flex items-center gap-2.5 text-[13px] text-ink_text-secondary cursor-pointer select-none">
            <input type="checkbox" wire:model="remember" class="rj-checkbox">
            Keep me signed in on this device
        </label>

        <button type="submit" wire:loading.attr="disabled" wire:target="login"
            class="press w-full h-12 rounded-control gold-sheen text-white text-[14.5px] font-bold shadow-gold border border-gold-dark/30 hover:brightness-[1.07] disabled:opacity-80 inline-flex items-center justify-center gap-2">
            <span wire:loading.remove wire:target="login" class="inline-flex items-center gap-2">Sign in <x-ui.icon name="arrow-right" :size="16" /></span>
            <span wire:loading.flex wire:target="login" class="items-center gap-2"><x-ui.icon name="loader" :size="16" class="animate-spin" /> Signing in</span>
        </button>
    </form>

    <div class="grid grid-cols-3 gap-2 mt-8">
        @foreach ([['receipt', 'Invoices'], ['star', 'Loyalty points'], ['calendar', 'Instalments']] as [$icon, $label])
            <div class="flex flex-col items-center gap-1.5 py-3.5 rounded-xl bg-white ring-1 ring-line-light text-center">
                <x-ui.icon :name="$icon" :size="17" class="text-gold-dark" />
                <span class="text-[12px] font-semibold text-ink_text-secondary">{{ $label }}</span>
            </div>
        @endforeach
    </div>
    <p class="text-[12.5px] text-ink_text-muted text-center mt-5">New to the portal, or forgot your password? Ask at the counter and we'll set it up.</p>
</x-auth.shell>
</div>
