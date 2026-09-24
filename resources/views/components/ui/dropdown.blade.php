@props(['align' => 'right', 'width' => 'w-52'])
{{--
    <x-ui.dropdown>
        <x-slot:trigger><x-ui.button variant="ghost" size="icon-sm" icon="more-horizontal" /></x-slot:trigger>
        <x-ui.dropdown-item icon="edit" wire:click="edit(1)">Edit</x-ui.dropdown-item>
    </x-ui.dropdown>
--}}
<div x-data="{ open: false }" class="relative inline-flex" x-on:keydown.escape="open = false" x-on:click.outside="open = false">
    <div x-on:click="open = !open" x-ref="trigger">{{ $trigger }}</div>
    <div x-show="open" x-cloak
         x-anchor.{{ $align === 'right' ? 'bottom-end' : 'bottom-start' }}.offset.6="$refs.trigger"
         x-transition:enter="transition ease-silk duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1 scale-[.98]"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-end="opacity-0"
         x-on:click="open = false"
         class="{{ $width }} z-dropdown bg-white rounded-xl border border-line-light shadow-pop p-1.5">
        {{ $slot }}
    </div>
</div>
