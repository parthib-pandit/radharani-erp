@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[11.5px] text-ink_text-secondary mb-1']) }}>
    {{ $value ?? $slot }}
</label>
