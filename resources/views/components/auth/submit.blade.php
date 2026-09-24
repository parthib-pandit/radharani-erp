@props(['busy' => 'Please wait'])
{{-- Full-width gold submit for plain (non-Livewire) auth forms. Put x-data="{ submitting: false }" x-on:submit="submitting = true" on the form. --}}
<button type="submit" :disabled="submitting"
    {{ $attributes->merge(['class' => 'press w-full h-12 rounded-control gold-sheen text-white text-[14.5px] font-bold shadow-gold border border-gold-dark/30 hover:brightness-[1.07] disabled:opacity-80 inline-flex items-center justify-center gap-2']) }}>
    <span x-show="!submitting" class="inline-flex items-center gap-2">{{ $slot }} <x-ui.icon name="arrow-right" :size="16" /></span>
    <span x-show="submitting" x-cloak class="inline-flex items-center gap-2"><x-ui.icon name="loader" :size="16" class="animate-spin" /> {{ $busy }}</span>
</button>
