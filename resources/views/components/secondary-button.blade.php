<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 h-10 px-4 rounded-lg text-[13px] font-semibold transition-colors bg-white text-ink_text-primary border border-line hover:bg-surface-muted disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
