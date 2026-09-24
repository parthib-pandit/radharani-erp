@props(['placeholder' => 'Search...'])
{{-- Debounced live search box with a clear button. Pass wire:model.live.debounce... through. --}}
@php $model = $attributes->wire('model')->value(); @endphp
<div class="rj-input-icon {{ $attributes->get('class') }}">
    <x-ui.icon name="search" :size="15" />
    <input type="search" placeholder="{{ $placeholder }}" autocomplete="off"
        {{ $attributes->except('class')->merge(['class' => 'rj-input pr-9 [&::-webkit-search-cancel-button]:hidden']) }}>
    @if ($model)
        <button type="button" x-data x-show="$wire.{{ $model }}" x-cloak x-on:click="$wire.set('{{ $model }}', '')"
            class="absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 rounded-md text-ink_text-muted hover:text-ink_text-primary hover:bg-surface-muted flex items-center justify-center"
            aria-label="Clear search">
            <x-ui.icon name="x" :size="13" />
        </button>
    @endif
</div>
