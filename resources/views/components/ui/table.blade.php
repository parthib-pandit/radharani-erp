@props(['headers' => []])
<div class="overflow-x-auto">
    <table class="w-full border-collapse text-[13.5px]">
        @if(count($headers))
            <thead>
                <tr class="h-11 bg-surface-muted">
                    @foreach($headers as $header)
                        <th class="text-left px-4 text-xs font-semibold text-ink_text-secondary uppercase tracking-wide">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {{ $slot }}
        </tbody>
    </table>
</div>
