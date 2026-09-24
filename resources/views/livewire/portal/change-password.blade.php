<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-[400px]">

        <a href="{{ route('portal.dashboard') }}" wire:navigate class="inline-flex items-center gap-1.5 text-[12.5px] text-ink_text-secondary font-semibold mb-4">
            <x-ui.icon name="chevron-left" :size="13" /> Back to dashboard
        </a>

        <x-ui.card class="!p-7">
            <div class="text-xl font-semibold">Change password</div>
            <div class="text-[12.5px] text-ink_text-secondary mt-1 mb-5">Update the password for your customer portal account.</div>

            @if ($saved)
                <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-4 text-sm">
                    Password updated.
                </div>
            @endif

            <form wire:submit="update" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">

                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Current password</label>
                <div class="relative mb-1">
                    <input :type="showCurrent ? 'text' : 'password'" wire:model="current_password" class="rj-input pr-14">
                    <button type="button" @click="showCurrent = !showCurrent"
                        class="absolute right-2 top-0 h-10 bg-transparent border-0 text-ink_text-secondary text-[11.5px] font-semibold">
                        <span x-text="showCurrent ? 'Hide' : 'Show'"></span>
                    </button>
                </div>
                @error('current_password') <div class="text-danger text-[11.5px] mb-3.5">{{ $message }}</div> @enderror

                <div class="h-px bg-line-light my-4"></div>

                <label class="block text-[11.5px] text-ink_text-secondary mb-1">New password</label>
                <div class="relative mb-1">
                    <input :type="showNew ? 'text' : 'password'" wire:model="password" class="rj-input pr-14">
                    <button type="button" @click="showNew = !showNew"
                        class="absolute right-2 top-0 h-10 bg-transparent border-0 text-ink_text-secondary text-[11.5px] font-semibold">
                        <span x-text="showNew ? 'Hide' : 'Show'"></span>
                    </button>
                </div>
                @error('password') <div class="text-danger text-[11.5px] mb-1.5">{{ $message }}</div> @enderror
                <div class="text-[11px] text-ink_text-secondary mb-3.5">At least 8 characters.</div>

                <label class="block text-[11.5px] text-ink_text-secondary mb-1">Confirm new password</label>
                <div class="relative mb-5">
                    <input :type="showConfirm ? 'text' : 'password'" wire:model="password_confirmation" class="rj-input pr-14">
                    <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute right-2 top-0 h-10 bg-transparent border-0 text-ink_text-secondary text-[11.5px] font-semibold">
                        <span x-text="showConfirm ? 'Hide' : 'Show'"></span>
                    </button>
                </div>

                <div class="flex justify-end gap-2.5 border-t border-line-light pt-4">
                    <a href="{{ route('portal.dashboard') }}" wire:navigate>
                        <x-ui.button type="button" variant="secondary">Cancel</x-ui.button>
                    </a>
                    <x-ui.button type="submit" variant="primary">Update Password</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>
