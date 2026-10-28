@props(['counts', 'total'])
{{-- Where the pieces in a box/packet are right now, in two words (28 Oct): In stock (here, or held for a sale nobody has verified
     yet) and Not here (out with a karigar or hallmarking, or waiting for review). The reason shows on hover and on the piece's own row. --}}
@php
$counts = collect($counts);
$here = (int) (($counts['in_stock'] ?? 0) + ($counts['reserved'] ?? 0));
$away = (int) (($counts['dispatched'] ?? 0) + ($counts['pending_review'] ?? 0) + ($counts['on_hold'] ?? 0));
$reasons = collect(['dispatched' => 'out with a karigar or hallmarking', 'pending_review' => 'waiting for review', 'on_hold' => 'not in a packet'])
    ->filter(fn ($label, $key) => ($counts[$key] ?? 0) > 0)->map(fn ($label, $key) => ($counts[$key] ?? 0) . ' ' . $label)->implode(' · ');
$rows = [['In stock', 'bg-success', $here, null], ['Not here', 'bg-warning', $away, $reasons]];
$sum = max(1, $here + $away);
@endphp
@if ($total > 0)
    <div class="flex h-2 rounded-full overflow-hidden gap-[2px]" role="img" aria-label="In stock and not here">
        @foreach ($rows as [$name, $color, $n, $why])
            @if ($n > 0)<div class="{{ $color }}" style="width: {{ round($n / $sum * 100, 2) }}%" title="{{ $name }}: {{ $n }}{{ $why ? ' (' . $why . ')' : '' }}"></div>@endif
        @endforeach
    </div>
    <dl class="grid grid-cols-2 gap-x-4 gap-y-2 mt-3.5">
        @foreach ($rows as [$name, $color, $n, $why])
            <div class="flex items-center justify-between gap-2 text-[12.5px]" @if ($why) title="{{ $why }}" @endif>
                <dt class="flex items-center gap-2 text-ink_text-secondary"><span class="w-2 h-2 rounded-sm {{ $color }}"></span>{{ $name }}</dt>
                <dd class="font-semibold tabular {{ $n ? 'text-ink_text-primary' : 'text-ink_text-muted' }}">{{ $n }}</dd>
            </div>
        @endforeach
    </dl>
    @if ($why = $rows[1][3])<p class="mt-2 text-[11.5px] text-ink_text-muted">Not here: {{ $why }}.</p>@endif
@else
    <p class="text-[13px] text-ink_text-muted">No pieces inside yet.</p>
@endif
