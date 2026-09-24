<div>
<x-auth.shell audience="customer" heading="Keep your account yours." lede="A strong password protects your invoices, loyalty points and instalment history.">
    <x-slot:aside>
        <a href="{{ route('portal.dashboard') }}" wire:navigate class="group inline-flex items-center gap-1.5 text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">
            <x-ui.icon name="arrow-left" :size="14" class="transition-transform group-hover:-translate-x-0.5" /> Back to your account
        </a>
    </x-slot:aside>

    <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">Account security</p>
    <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Change password</h1>
    <p class="text-[14px] text-ink_text-secondary mt-2">Enter your current password, then choose a new one of at least 8 characters.</p>

    @if ($saved)
        <div class="flex items-start gap-2.5 mt-6 px-4 py-3 rounded-xl bg-success-bg text-success text-[13px] font-medium">
            <x-ui.icon name="check-circle" :size="16" class="shrink-0 mt-px" /> Password updated. Use the new one next time you sign in.
        </div>
    @endif

    <form wire:submit="update" class="mt-8 space-y-5">
        <div>
            <label for="current_password" class="rj-label">Current password</label>
            <x-ui.password-input id="current_password" wire:model="current_password" autocomplete="current-password" autofocus :invalid="$errors->has('current_password')" />
            @error('current_password')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @enderror
        </div>

        <div class="h-px bg-line-light"></div>

        <div>
            <label for="new_password" class="rj-label">New password</label>
            <x-ui.password-input id="new_password" wire:model="password" autocomplete="new-password" :invalid="$errors->has('password')" />
            @error('password')
                <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
            @else
                <p class="rj-help">At least 8 characters.</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="rj-label">Type it again</label>
            <x-ui.password-input id="password_confirmation" wire:model="password_confirmation" autocomplete="new-password" />
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="update"
            class="press w-full h-12 rounded-control gold-sheen text-white text-[14.5px] font-bold shadow-gold border border-gold-dark/30 hover:brightness-[1.07] disabled:opacity-80 inline-flex items-center justify-center gap-2">
            <span wire:loading.remove wire:target="update" class="inline-flex items-center gap-2">Update password <x-ui.icon name="check" :size="16" /></span>
            <span wire:loading.flex wire:target="update" class="items-center gap-2"><x-ui.icon name="loader" :size="16" class="animate-spin" /> Saving</span>
        </button>
    </form>
</x-auth.shell>
</div>
