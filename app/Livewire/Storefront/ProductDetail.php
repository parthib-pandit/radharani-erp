<?php
namespace App\Livewire\Storefront;

use App\Models\Stock\Item;
use App\Services\PricingService;
use Livewire\Component;

/**
 * Product Detail — public, no login required.
 *
 * FLAG: there's no shop contact number anywhere in config or the schema
 * (no settings table), so the WhatsApp link below uses a clearly-marked
 * placeholder number — swap config('app.shop_whatsapp') in once that
 * setting exists rather than hardcoding a real number here.
 */
class ProductDetail extends Component
{
    public Item $item;

    public function mount(Item $item)
    {
        $this->item = $item;
    }

    public function render()
    {
        $price = app(PricingService::class)->priceFor($this->item);

        // Placeholder — no shop contact number configured anywhere yet.
        $whatsappNumber = '910000000000';
        $message = urlencode("Hi, I'm interested in {$this->item->category} ({$this->item->internal_code}) listed at ₹" . number_format($price, 2));

        return view('livewire.storefront.product-detail', [
            'price' => $price,
            'whatsappUrl' => "https://wa.me/{$whatsappNumber}?text={$message}",
        ])->layout('components.layouts.guest');
    }
}
