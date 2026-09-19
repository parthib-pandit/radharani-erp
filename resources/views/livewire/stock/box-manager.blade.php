<x-layouts.app title="Boxes — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Box Management</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">Top-level storage containers — packets live inside boxes.</div>

    @if (session('message'))
        <div class="rj-flash">{{ session('message') }}</div>
    @endif

    <div class="rj-card" style="margin-bottom:24px;">
        <div style="font-weight:700;font-size:14px;margin-bottom:14px;">{{ $editingId ? 'Edit Box' : 'New Box' }}</div>
        <form wire:submit="save" style="display:flex;flex-wrap:wrap;gap:14px;align-items:end;">
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Code</label>
                <input type="text" wire:model="code" class="rj-input">
                @error('code') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Label</label>
                <input type="text" wire:model="label" class="rj-input">
            </div>
            <button type="submit" class="rj-btn-primary">Save</button>
            @if ($editingId)
                <button type="button" wire:click="cancel" class="rj-btn-secondary">Cancel</button>
            @endif
        </form>
    </div>

    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search boxes..."
        class="rj-input" style="margin-bottom:14px;width:280px;">

    <div class="rj-card" style="padding:0;overflow:hidden;">
        <table class="rj-table">
            <thead>
                <tr><th style="padding-left:20px;">Code</th><th>Label</th><th># Packets</th><th style="padding-right:20px;"></th></tr>
            </thead>
            <tbody>
                @foreach ($boxes as $box)
                    <tr>
                        <td style="padding-left:20px;font-weight:600;">{{ $box->code }}</td>
                        <td>{{ $box->label }}</td>
                        <td>{{ $box->packets_count }}</td>
                        <td style="padding-right:20px;text-align:right;">
                            <button wire:click="edit({{ $box->id }})" style="background:none;border:none;color:var(--accent);font-weight:700;font-size:12.5px;cursor:pointer;">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">{{ $boxes->links() }}</div>
</div>
</x-layouts.app>
