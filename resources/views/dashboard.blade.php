<x-layouts.app title="Dashboard — Radharani Jewellery ERP">
    <x-ui.page-header title="Hello, {{ auth()->user()->name ?? 'there' }}" subtitle="Here's an overview of your jewellery business.">
        <x-slot:actions>
            <x-ui.button variant="secondary" icon="calendar">This Month</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-ui.stat-card icon="box" label="Total Inventory (Items)" :value="\App\Models\Stock\Item::count()" />
        <x-ui.stat-card icon="cart" label="Total Purchase Value" value="—" />
        <x-ui.stat-card icon="receipt" label="Total Sales Value" value="—" />
        <x-ui.stat-card icon="bar-chart" label="Gross Profit" value="—" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <a href="{{ route('stock.items') }}" class="block">
            <x-ui.card class="hover:border-gold transition-colors h-full">
                <div class="w-9 h-9 rounded-control bg-[#FFF7E6] text-gold flex items-center justify-center mb-3">
                    <x-ui.icon name="gem" :size="17" />
                </div>
                <div class="font-semibold text-sm text-ink_text-primary">Inventory</div>
                <div class="text-xs text-ink_text-secondary mt-1">Manage items</div>
            </x-ui.card>
        </a>
        <a href="{{ route('stock.boxes') }}" class="block">
            <x-ui.card class="hover:border-gold transition-colors h-full">
                <div class="w-9 h-9 rounded-control bg-[#FFF7E6] text-gold flex items-center justify-center mb-3">
                    <x-ui.icon name="archive" :size="17" />
                </div>
                <div class="font-semibold text-sm text-ink_text-primary">Boxes & Packets</div>
                <div class="text-xs text-ink_text-secondary mt-1">Manage containers</div>
            </x-ui.card>
        </a>
        <a href="{{ route('wireframes.index') }}" class="block">
            <x-ui.card class="hover:border-gold transition-colors h-full">
                <div class="w-9 h-9 rounded-control bg-[#FFF7E6] text-gold flex items-center justify-center mb-3">
                    <x-ui.icon name="layers" :size="17" />
                </div>
                <div class="font-semibold text-sm text-ink_text-primary">Wireframes</div>
                <div class="text-xs text-ink_text-secondary mt-1">Client-approved reference screens</div>
            </x-ui.card>
        </a>
    </div>
</x-layouts.app>
