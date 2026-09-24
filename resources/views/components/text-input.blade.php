@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rj-input w-full']) }}>
