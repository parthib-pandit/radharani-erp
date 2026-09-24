@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-danger text-[11.5px] mt-1.5 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
