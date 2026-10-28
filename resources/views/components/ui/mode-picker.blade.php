@props(['model', 'value' => 'cash', 'modes' => \App\Models\Sales\SalePayment::NEW_MODES])
{{-- Cash or Bank as two icon buttons (28 Oct). Sets a Livewire property: <x-ui.mode-picker model="payments.0.mode" :value="$p['mode']" /> --}}
@php $icons = ['cash' => 'banknote', 'bank' => 'landmark']; @endphp
<div {{ $attributes->class('inline-flex gap-1.5 shrink-0') }} role="radiogroup" aria-label="How it is paid">
    @foreach ($modes as $key => $label)
        <button type="button" role="radio" aria-checked="{{ $value === $key ? 'true' : 'false' }}" wire:click="$set('{{ $model }}', '{{ $key }}')" title="{{ $label }}"
            @class([
                'press h-11 min-w-[78px] px-3 rounded-control border inline-flex flex-col items-center justify-center gap-0.5 text-[11.5px] font-semibold transition',
                'gold-sheen text-white border-gold-dark/30 shadow-gold' => $value === $key,
                'bg-white text-ink_text-secondary border-line hover:border-line-strong hover:bg-surface-sunken' => $value !== $key,
            ])>
            <x-ui.icon :name="$icons[$key] ?? 'coins'" :size="17" />
            {{ $label }}
        </button>
    @endforeach
</div>
