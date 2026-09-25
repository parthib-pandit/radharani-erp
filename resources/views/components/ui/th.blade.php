@props(['field' => null, 'sortField' => null, 'sortDirection' => 'asc', 'align' => 'left'])
{{-- Sortable header cell for server-side tables using the WithDataTable trait. --}}
@php
$active = $field && $sortField === $field;
$alignClass = ['left' => 'text-left', 'right' => 'text-right', 'center' => 'text-center'][$align] ?? 'text-left';
@endphp
<th {{ $attributes->merge(['class' => $alignClass]) }} @if($active) aria-sort="{{ $sortDirection === 'asc' ? 'ascending' : 'descending' }}" @endif>
    @if ($field)
        <button type="button" wire:click="sortBy('{{ $field }}')"
            class="group/th inline-flex items-center gap-1 uppercase tracking-[0.06em] {{ $active ? 'text-ink_text-primary' : 'hover:text-ink_text-primary' }} {{ $align === 'right' ? 'flex-row-reverse' : '' }}">
            {{ $slot }}
            <span class="{{ $active ? 'text-gold' : 'text-ink_text-muted/50 group-hover/th:text-ink_text-muted' }}">
                @if ($active)
                    <x-ui.icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" :size="13" />
                @else
                    <x-ui.icon name="chevrons-up-down" :size="12" />
                @endif
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
