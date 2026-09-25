@props(['invalid' => false])
{{-- Password field with a show/hide toggle. Pass name/id/wire:model/autocomplete straight through. --}}
<div class="relative" x-data="{ reveal: false }">
    <x-ui.icon name="lock" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-ink_text-muted pointer-events-none" />
    <input :type="reveal ? 'text' : 'password'" type="password"
        {{ $attributes->merge(['class' => 'rj-input h-12 pl-10 pr-12 text-[14px]' . ($invalid ? ' is-invalid' : '')]) }}>
    <button type="button" x-on:click="reveal = !reveal" :aria-label="reveal ? 'Hide password' : 'Show password'"
        class="absolute right-1.5 top-1/2 -translate-y-1/2 w-9 h-9 rounded-lg text-ink_text-muted hover:text-ink_text-primary hover:bg-surface-muted flex items-center justify-center">
        <x-ui.icon name="eye" :size="16" x-show="!reveal" />
        <x-ui.icon name="eye-off" :size="16" x-show="reveal" x-cloak />
    </button>
</div>
