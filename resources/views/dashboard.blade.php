<x-layouts.app title="Dashboard — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Dashboard</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">Quick links while the live dashboard is being built.</div>

    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        <a href="{{ route('stock.items') }}" class="rj-card" style="display:block;">
            <div style="font-weight:700;font-size:14px;">Inventory</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px;">Manage items</div>
        </a>
        <a href="{{ route('stock.boxes') }}" class="rj-card" style="display:block;">
            <div style="font-weight:700;font-size:14px;">Boxes & Packets</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px;">Manage containers</div>
        </a>
        <a href="{{ route('wireframes.index') }}" class="rj-card" style="display:block;">
            <div style="font-weight:700;font-size:14px;">Wireframes</div>
            <div style="font-size:12px;color:var(--muted);margin-top:4px;">Client-approved reference screens</div>
        </a>
    </div>
</div>
</x-layouts.app>