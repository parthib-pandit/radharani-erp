@props(['icon' => 'inbox', 'title', 'message' => null, 'compact' => false])
<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center ' . ($compact ? 'py-8 px-4' : 'py-14 px-6')]) }}>
    <div class="relative mb-4">
        <div class="absolute inset-0 rounded-2xl bg-gold-soft/40 blur-xl"></div>
        <div class="relative {{ $compact ? 'w-11 h-11' : 'w-14 h-14' }} rounded-2xl bg-white ring-1 ring-line shadow-card text-gold flex items-center justify-center">
            <x-ui.icon :name="$icon" :size="$compact ? 18 : 22" />
        </div>
    </div>
    <div class="text-[14.5px] font-bold text-ink_text-primary">{{ $title }}</div>
    @if ($message)
        <p class="text-[13px] text-ink_text-secondary mt-1 max-w-[46ch]">{{ $message }}</p>
    @endif
    @if (trim($slot) !== '')
        <div class="flex flex-wrap items-center justify-center gap-2.5 mt-5">{{ $slot }}</div>
    @endif
</div>
