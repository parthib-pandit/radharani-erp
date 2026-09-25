@props(['headers' => []])
{{-- headers: strings, or ['label' => ..., 'class' => ...] for alignment/width. Rows go in the slot. --}}
<div class="relative overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'rj-table']) }}>
        @if (count($headers))
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        @php $h = is_array($header) ? $header : ['label' => $header]; @endphp
                        <th class="{{ $h['class'] ?? '' }}">{{ $h['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
        @elseif (isset($head))
            <thead><tr>{{ $head }}</tr></thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
