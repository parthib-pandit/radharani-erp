@props(['label' => null, 'for' => null, 'error' => null, 'hint' => null, 'optional' => false])
{{-- Label above, control in the slot, help text and the validation error below. --}}
<div {{ $attributes->merge(['class' => 'min-w-0']) }}>
    @if ($label)
        <label @if($for) for="{{ $for }}" @endif class="rj-label">
            {{ $label }}
            @if ($optional)<span class="rj-label-hint">(optional)</span>@endif
        </label>
    @endif
    {{ $slot }}
    @if ($error)
        @error($error)
            <p class="rj-error"><x-ui.icon name="alert-triangle" :size="12" class="shrink-0" />{{ $message }}</p>
        @else
            @if ($hint)<p class="rj-help">{{ $hint }}</p>@endif
        @enderror
    @elseif ($hint)
        <p class="rj-help">{{ $hint }}</p>
    @endif
</div>
