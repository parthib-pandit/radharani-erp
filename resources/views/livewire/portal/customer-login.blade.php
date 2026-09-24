<div class="min-h-screen flex">
    {{-- Form panel --}}
    <div class="flex-1 flex items-center justify-center p-10">
        <div class="w-full max-w-[360px]">
            <div class="flex items-center gap-2.5 mb-8">
                <div class="w-[34px] h-[34px] shrink-0 rounded-[9px] bg-gradient-to-br from-gold-light to-gold flex items-center justify-center">
                    <x-ui.icon name="gem" :size="17" class="text-ink" />
                </div>
                <div class="text-sm font-bold tracking-wide">RADHARANI ERP</div>
            </div>

            <div class="text-[11px] font-semibold tracking-widest text-gold uppercase mb-2">Customer Portal</div>
            <div class="text-xl font-semibold mb-1">Sign in to your account</div>
            <div class="text-[13px] text-ink_text-secondary mb-6">Use your registered phone number and password.</div>

            <form wire:submit="login">
                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Phone number</label>
                <input type="text" wire:model="phone" class="rj-input mb-1">
                @error('phone') <div class="text-danger text-[11.5px] mb-3 mt-1">{{ $message }}</div> @enderror

                <label class="block text-[11.5px] text-ink_text-secondary mb-1 mt-4">Password</label>
                <input type="password" wire:model="password" class="rj-input mb-5">

                <x-ui.button type="submit" variant="primary" class="w-full">Sign In</x-ui.button>
            </form>

            <div class="text-[11.5px] text-ink_text-secondary text-center mt-5">
                New to the portal? Ask your jeweller for access.
            </div>
        </div>
    </div>

    {{-- Brand panel --}}
    <div class="w-[44%] min-w-[380px] bg-ink text-white/90 p-14 flex flex-col justify-between">
        <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase">Radharani ERP · Customer Portal</div>
        <div>
            <div class="text-3xl font-semibold leading-tight text-white max-w-[360px]">Your jewellery, always in view.</div>
            <div class="text-[13.5px] text-[#C9C4B8] leading-relaxed mt-3.5 max-w-[340px]">
                Sign in to track your orders, view invoices and browse your saved collection — anytime.
            </div>
            <div class="flex flex-col gap-3 mt-7">
                @foreach ([
                    'Live order and manufacturing status',
                    'Digital invoices and receipts',
                    'Your saved jewellery collection',
                ] as $line)
                <div class="flex items-center gap-2.5">
                    <span class="w-[22px] h-[22px] shrink-0 rounded-full bg-white/10 flex items-center justify-center">
                        <x-ui.icon name="check" :size="11" class="text-gold-light" />
                    </span>
                    <span class="text-[12.5px] text-[#C9C4B8]">{{ $line }}</span>
                </div>
                @endforeach
            </div>
        </div>
        <div class="text-[11px] text-[#8E8A80]">© {{ date('Y') }} Radharani ERP · Customer access · Powered by Echocrew</div>
    </div>
</div>
