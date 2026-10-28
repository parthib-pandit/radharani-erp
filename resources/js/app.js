// Alpine is bundled and started by Livewire v3 itself (window.Alpine is set
// once Livewire's own script runs) — importing/starting a second copy here
// caused "Detected multiple instances of Alpine running" and broke
// wire:model reactivity on any element that also had x-data. Don't
// reintroduce a manual Alpine.start() here.

// Camera barcode / QR scanner (components/layouts/partials/scanner.blade.php).
// Loaded on first use only, so pages that never open the camera don't pay for it.
window.rjLoadScanner = () => import('html5-qrcode');

// Amounts, weights and counts are typed in plain fields, never <input type="number">: there a scroll of the wheel or
// an arrow key changes the value by mistake. inputmode brings up the number keypad on a phone; this keeps what is typed
// to digits and one decimal point (a minus sign only where the field says data-signed). It runs before Livewire reads
// the field, so the server never sees the stray characters.
document.addEventListener('input', (e) => {
    const el = e.target;
    if (!(el instanceof HTMLInputElement) || el.type !== 'text') return;
    const mode = el.getAttribute('inputmode');
    if (mode !== 'decimal' && mode !== 'numeric') return;

    let v = el.value.replace(/[^0-9.\-]/g, '');
    const negative = el.hasAttribute('data-signed') && v.startsWith('-');
    v = v.replace(/-/g, '');
    if (mode === 'numeric') {
        v = v.replace(/\./g, '');
    } else {
        const i = v.indexOf('.');
        if (i !== -1) v = v.slice(0, i + 1) + v.slice(i + 1).replace(/\./g, '');
    }
    v = (negative ? '-' : '') + v;
    if (v !== el.value) el.value = v;
}, true);
