<x-layouts.app title="Inventory — Radharani Jewellery">
<div>
    <div class="rj-serif" style="font-size:24px;margin-bottom:4px;">Inventory</div>
    <div style="font-size:13px;color:var(--muted);margin-bottom:24px;">Full item list — HUID or auto-generated code, purity, weight, status.</div>

    @if (session('message'))
        <div class="rj-flash">{{ session('message') }}</div>
    @endif

    <div class="rj-card" style="margin-bottom:24px;">
        <div style="font-weight:700;font-size:14px;margin-bottom:14px;">{{ $editingId ? 'Edit Item' : 'New Item' }}</div>
        <form wire:submit="save" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Packet</label>
                <select wire:model="packet_id" class="rj-select" style="width:100%;">
                    <option value="">— none —</option>
                    @foreach ($packets as $packet)
                        <option value="{{ $packet->id }}">{{ $packet->code }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">HUID (blank if none)</label>
                <input type="text" wire:model="huid_code" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Category</label>
                <input type="text" wire:model="category" class="rj-input" style="width:100%;">
                @error('category') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Purity</label>
                <input type="text" wire:model="purity" placeholder="22K / 92.5 silver" class="rj-input" style="width:100%;">
                @error('purity') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Weight (g)</label>
                <input type="number" step="0.001" wire:model="weight" class="rj-input" style="width:100%;">
                @error('weight') <div style="color:#B04A3C;font-size:11px;margin-top:3px;">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">HSN Code</label>
                <input type="text" wire:model="hsn_code" class="rj-input" style="width:100%;">
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Making Type</label>
                <select wire:model="making_type" class="rj-select" style="width:100%;">
                    <option value="per_piece">Per Piece</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>
            <div>
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Making Value</label>
                <input type="number" step="0.01" wire:model="making_value" class="rj-input" style="width:100%;">
            </div>
            <div style="grid-column:span 2;">
                <label style="display:block;font-size:11.5px;color:var(--muted);margin-bottom:4px;">Description (short)</label>
                <input type="text" wire:model="description" maxlength="100" class="rj-input" style="width:100%;">
            </div>
            @if (!$editingId)
            <div style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" wire:model="has_pair" id="has_pair">
                <label for="has_pair" style="font-size:12.5px;">Create as pair (earrings/bangles)</label>
            </div>
            @endif
            <div style="grid-column:1/-1;display:flex;gap:10px;">
                <button type="submit" class="rj-btn-primary">Save</button>
                @if ($editingId)
                    <button type="button" wire:click="cancel" class="rj-btn-secondary">Cancel</button>
                @endif
            </div>
        </form>
    </div>

    <div style="display:flex;gap:12px;margin-bottom:14px;">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search HUID / code / category..."
            class="rj-input" style="width:280px;">
        <select wire:model.live="statusFilter" class="rj-select">
            <option value="">All statuses</option>
            <option value="in_stock">In Stock</option>
            <option value="dispatched">Dispatched</option>
            <option value="sold">Sold</option>
        </select>
    </div>

    <div class="rj-card" style="padding:0;overflow:hidden;">
        <table class="rj-table">
            <thead>
                <tr>
                    <th style="padding-left:20px;">ID / HUID</th><th>Category</th><th>Purity</th>
                    <th>Weight</th><th>Packet</th><th>Status</th><th style="padding-right:20px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td style="padding-left:20px;font-weight:600;color:var(--accent);">{{ $item->huid_code ?: $item->internal_code }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ $item->purity }}</td>
                        <td>{{ $item->weight }}g</td>
                        <td>{{ $item->packet?->code ?? '—' }}</td>
                        <td>
                            <span class="rj-tag {{ $item->status === 'in_stock' ? 'rj-tag-stock' : ($item->status === 'dispatched' ? 'rj-tag-dispatched' : 'rj-tag-sold') }}">
                                {{ strtoupper(str_replace('_',' ',$item->status)) }}
                            </span>
                        </td>
                        <td style="padding-right:20px;text-align:right;">
                            <button wire:click="edit({{ $item->id }})" style="background:none;border:none;color:var(--accent);font-weight:700;font-size:12.5px;cursor:pointer;">Edit</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">{{ $items->links() }}</div>
</div>
</x-layouts.app>
