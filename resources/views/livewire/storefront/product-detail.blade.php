<div class="min-h-screen bg-surface-bg text-ink_text-primary">
  <div class="max-w-[640px] mx-auto px-5 pt-8 pb-16">

    <a href="{{ route('storefront.catalog') }}" wire:navigate class="text-[12.5px] text-gold font-semibold">&larr; Back to Catalog</a>

    <x-ui.card class="!p-0 overflow-hidden mt-4">
      <div class="h-[260px] bg-gradient-to-br from-[#FBF3E6] to-[#F3EFE8] flex items-center justify-center text-gold text-lg font-medium">
        {{ ucfirst($item->category) }}
      </div>
      <div class="p-6">
        <div class="text-2xl font-bold text-ink_text-primary">{{ ucfirst($item->category) }} — {{ $item->purity }}</div>
        <div class="text-[13px] text-ink_text-secondary mt-1">{{ number_format($item->weight, 3) }}g @if($item->huid_code) · HUID {{ $item->huid_code }} @endif</div>

        @if ($item->description)
          <div class="text-[13.5px] text-ink_text-primary mt-4 leading-relaxed">{{ $item->description }}</div>
        @endif

        <div class="text-[28px] font-bold text-ink_text-primary mt-5">₹{{ number_format($price, 2) }}</div>
        <div class="text-[11.5px] text-ink_text-secondary mt-0.5">Price updates with today's rate — confirm at the counter.</div>

        <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="inline-block mt-5">
          <x-ui.button variant="primary" icon="phone">Ask on WhatsApp</x-ui.button>
        </a>
      </div>
    </x-ui.card>
  </div>
</div>
