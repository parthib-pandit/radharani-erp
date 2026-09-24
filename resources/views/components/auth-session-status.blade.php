@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'bg-success-bg text-success rounded-control px-3.5 py-2.5 text-sm']) }}>
        {{ $status }}
    </div>
@endif
