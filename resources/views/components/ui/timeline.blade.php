@props(['events', 'emptyTitle' => 'No history yet', 'emptyMessage' => null])
{{-- Renders StockHistoryService events. Shared by Item, Packet and Box Detail so all three read the same way. --}}
@php
$tones = [
    'out' => 'bg-warning-bg text-warning ring-warning/20',
    'in' => 'bg-success-bg text-success ring-success/20',
    'gold' => 'bg-gold-tint text-gold-dark ring-gold-soft',
    'neutral' => 'bg-surface-muted text-ink_text-secondary ring-line',
];
$grouped = collect($events)->groupBy(fn ($e) => $e['at']->isToday() ? 'Today' : ($e['at']->isYesterday() ? 'Yesterday' : $e['at']->format('d M Y')));
@endphp
@if (collect($events)->isEmpty())
    <x-ui.empty-state icon="history" :title="$emptyTitle" :message="$emptyMessage" compact />
@else
    <div {{ $attributes->merge(['class' => 'space-y-6']) }}>
        @foreach ($grouped as $day => $dayEvents)
            <div>
                <div class="sticky top-16 z-[1] -mx-1 px-1 py-1 mb-2 bg-white/95 backdrop-blur-sm">
                    <span class="text-[11.5px] font-bold uppercase tracking-[0.1em] text-ink_text-muted">{{ $day }}</span>
                </div>
                <ol class="relative">
                    @foreach ($dayEvents as $e)
                        <li class="relative flex gap-4 pb-5 last:pb-0 group/ev">
                            @unless ($loop->last)
                                <span class="absolute left-[15px] top-9 bottom-0 w-px bg-line" aria-hidden="true"></span>
                            @endunless
                            <span class="relative w-8 h-8 shrink-0 rounded-full ring-1 ring-inset flex items-center justify-center {{ $tones[$e['tone']] ?? $tones['neutral'] }}">
                                <x-ui.icon :name="$e['icon']" :size="14" />
                            </span>
                            <div class="min-w-0 flex-1 pt-1">
                                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-0.5">
                                    <div class="text-[13.5px] font-semibold text-ink_text-primary">
                                        @if ($e['link'])
                                            <a href="{{ $e['link'] }}" class="text-ink_text-primary hover:text-gold-dark">{{ $e['title'] }}</a>
                                        @else
                                            {{ $e['title'] }}
                                        @endif
                                    </div>
                                    <time class="text-[12px] text-ink_text-muted tabular whitespace-nowrap" datetime="{{ $e['at']->toIso8601String() }}" title="{{ $e['at']->format('d M Y, g:i a') }}">
                                        {{ $e['at']->format('g:i a') }}
                                    </time>
                                </div>
                                @if (count($e['meta']) || $e['user'])
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                        @foreach ($e['meta'] as $m)
                                            <span class="inline-flex items-center h-6 px-2 rounded-md bg-surface-sunken ring-1 ring-inset ring-line-light text-[12px] text-ink_text-secondary">{{ $m }}</span>
                                        @endforeach
                                        @if ($e['user'])
                                            <span class="inline-flex items-center gap-1 text-[12px] text-ink_text-muted ml-0.5">
                                                <x-ui.icon name="user" :size="12" /> {{ $e['user'] }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                @if ($e['note'])
                                    <p class="mt-2 text-[13px] text-ink_text-secondary leading-relaxed border-l-2 border-gold-soft pl-3">{{ $e['note'] }}</p>
                                @endif
                                @if ($e['photo'])
                                    <a href="{{ $e['photo'] }}" target="_blank" rel="noopener" class="mt-2.5 inline-block">
                                        <img src="{{ $e['photo'] }}" alt="Photo taken for this movement" loading="lazy"
                                             class="h-20 w-28 object-cover rounded-lg ring-1 ring-line hover:ring-gold transition">
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        @endforeach
    </div>
@endif
