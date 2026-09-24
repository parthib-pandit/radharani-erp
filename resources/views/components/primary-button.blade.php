<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 h-10 px-4 rounded-lg text-[13px] font-semibold transition-colors bg-gold text-white hover:bg-gold-dark disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
