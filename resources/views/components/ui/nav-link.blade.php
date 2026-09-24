@props(['route', 'icon', 'label'])
@php
    $active = request()->routeIs($route) || request()->routeIs($route.'.*');
    $href = \Illuminate\Support\Facades\Route::has($route) ? route($route) : '#';
@endphp
<a href="{{ $href }}"
   class="flex items-center gap-3 h-11 px-3.5 rounded-control text-sm font-medium transition-colors
   {{ $active ? 'bg-gradient-to-br from-gold to-gold-dark text-white' : 'text-[#C9C4B8] hover:bg-white/5' }}">
    <x-ui.icon :name="$icon" :size="16" class="shrink-0 {{ $active ? 'text-white' : 'text-[#8E8A80]' }}" />
    <span class="truncate">{{ $label }}</span>
</a>
