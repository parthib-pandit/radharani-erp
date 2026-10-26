{{--
    Camera scanner for phones: the shop has no handheld scanners, so every code field can open this. It opens full screen,
    like a payment app's scanner: the camera behind, close and torch on top, a Search pill at the bottom for typing a
    HUID, code or name instead (the result is handed over like a scan), and when it keeps scanning a count with Done.
    Open it from anywhere with <x-ui.scan-button> or:
        $dispatch('rj-scan', { target: '#input-id', submit: 'enter' | 'form' | false, continuous: true, title: '...' })
        $dispatch('rj-scan', { mode: 'go' })   // top bar: open whatever was scanned
    A read fills the target input as if it had been typed. submit 'enter' then presses Enter on it,
    'form' submits its form; continuous keeps the camera open for the next tag.
--}}
<div x-data="rjScanner()" x-on:rj-scan.window="openWith($event.detail)" x-on:keydown.escape.window="searching ? closeSearch() : (open && close())"
     x-show="open" x-cloak class="fixed inset-0 z-toast bg-black text-white overflow-hidden" role="dialog" aria-modal="true" aria-label="Camera scanner"
     x-transition:enter="transition ease-silk duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

    {{-- The camera fills the whole screen --}}
    <div x-ref="viewport" class="absolute inset-0 flex items-center justify-center overflow-hidden bg-black">
        <div id="rj-scan-region" class="w-full origin-center transition-transform duration-200"></div>
    </div>

    {{-- Aiming frame --}}
    <div x-show="status === 'scanning'" class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <div class="relative w-[78%] max-w-[360px] aspect-[3/2] rounded-2xl shadow-[0_0_0_9999px_rgba(0,0,0,.45)]">
            <span class="absolute -left-0.5 -top-0.5 w-9 h-9 border-l-[3px] border-t-[3px] border-gold-light rounded-tl-2xl"></span>
            <span class="absolute -right-0.5 -top-0.5 w-9 h-9 border-r-[3px] border-t-[3px] border-gold-light rounded-tr-2xl"></span>
            <span class="absolute -left-0.5 -bottom-0.5 w-9 h-9 border-l-[3px] border-b-[3px] border-gold-light rounded-bl-2xl"></span>
            <span class="absolute -right-0.5 -bottom-0.5 w-9 h-9 border-r-[3px] border-b-[3px] border-gold-light rounded-br-2xl"></span>
            <span class="rj-scanline absolute left-4 right-4 h-[2px] rounded-full bg-gold-light shadow-[0_0_12px_rgba(212,175,90,.9)]"></span>
        </div>
    </div>

    {{-- Read flash --}}
    <div x-show="flash" x-transition.opacity.duration.150ms class="absolute inset-0 bg-gold-light/25 pointer-events-none"></div>
    <div x-show="flash && lastCode" x-transition class="absolute left-1/2 -translate-x-1/2 top-[22%] px-4 h-10 rounded-full bg-white text-ink shadow-pop flex items-center gap-2 text-[13px] font-semibold">
        <x-ui.icon name="check-circle" :size="16" class="text-success" /> <span class="font-mono tracking-wide" x-text="lastCode"></span>
    </div>

    {{-- Starting --}}
    <div x-show="status === 'starting'" class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-ink-fg">
        <x-ui.icon name="loader" :size="26" class="animate-spin text-gold-light" />
        <span class="text-[13px]">Starting the camera</span>
    </div>

    {{-- Error --}}
    <div x-show="status === 'error'" class="absolute inset-0 flex flex-col items-center justify-center text-center gap-3 px-8">
        <span class="w-14 h-14 rounded-2xl bg-white/5 ring-1 ring-white/10 text-gold-light flex items-center justify-center"><x-ui.icon name="camera" :size="24" /></span>
        <p class="text-[14px] font-semibold text-white">Camera not available</p>
        <p class="text-[13px] text-ink-fg max-w-[34ch]" x-text="error"></p>
        <div class="flex gap-2 mt-1">
            <button type="button" x-on:click="restart()" class="press h-10 px-4 rounded-lg text-[13px] font-semibold ring-1 ring-white/15 hover:bg-white/10">Try again</button>
            <button type="button" x-on:click="openSearch()" class="press h-10 px-4 rounded-lg text-[13px] font-semibold gold-sheen text-ink">Search instead</button>
        </div>
    </div>

    {{-- Top bar: close on the left, the torch and camera on the right --}}
    <div class="absolute top-0 inset-x-0 z-10 flex items-center gap-3 px-3 pt-[max(.75rem,env(safe-area-inset-top))] pb-8 bg-gradient-to-b from-black/75 to-transparent pointer-events-none">
        <button type="button" x-on:click="close()" aria-label="Close scanner"
            class="press pointer-events-auto w-11 h-11 rounded-full bg-black/40 backdrop-blur ring-1 ring-white/15 text-white flex items-center justify-center shrink-0">
            <x-ui.icon name="x" :size="20" />
        </button>
        <div class="flex-1 min-w-0 text-center">
            <div class="font-display text-[20px] leading-tight font-semibold text-gold-light truncate" x-text="title"></div>
            <div class="text-[12px] text-white/70" x-text="continuous ? 'Keeps scanning. Tap Done when finished.' : 'Point the camera at the QR sticker or barcode.'"></div>
        </div>
        <div class="flex gap-2 shrink-0 min-w-[44px] justify-end pointer-events-auto">
            <button type="button" x-show="torchSupported && status === 'scanning'" x-on:click="toggleTorch()" aria-label="Torch"
                :class="torchOn ? 'bg-gold-light text-ink' : 'bg-black/40 text-white'"
                class="press w-11 h-11 rounded-full backdrop-blur flex items-center justify-center ring-1 ring-white/15">
                <x-ui.icon name="flashlight" :size="18" />
            </button>
            <button type="button" x-show="cameras.length > 1 && status === 'scanning'" x-on:click="switchCamera()" aria-label="Switch camera"
                class="press w-11 h-11 rounded-full bg-black/40 text-white backdrop-blur flex items-center justify-center ring-1 ring-white/15">
                <x-ui.icon name="switch-camera" :size="18" />
            </button>
        </div>
    </div>

    {{-- Bottom: the count and Done when it keeps scanning, and the Search pill --}}
    <div class="absolute bottom-0 inset-x-0 z-10 px-4 pt-14 pb-[max(1.25rem,env(safe-area-inset-bottom))] bg-gradient-to-t from-black/85 via-black/55 to-transparent">
        <div class="max-w-[520px] mx-auto space-y-3.5">
            <template x-if="continuous && results.length">
                <div class="flex gap-1.5 overflow-x-auto pb-0.5 [scrollbar-width:none]">
                    <template x-for="r in results" :key="r.at + r.code">
                        <span class="shrink-0 h-8 px-3 rounded-lg bg-white/[.12] ring-1 ring-white/15 text-[12.5px] font-mono tracking-wide flex items-center" x-text="r.code"></span>
                    </template>
                </div>
            </template>

            <div class="flex items-center gap-3" x-show="continuous">
                <div class="flex items-baseline gap-2 flex-1 min-w-0">
                    <span class="font-display text-[34px] leading-none font-semibold text-gold-light tabular" x-text="results.length"></span>
                    <span class="text-[13px] text-white/75">scanned</span>
                </div>
                <button type="button" x-on:click="close()"
                    class="press h-12 px-7 rounded-xl gold-sheen text-ink text-[14.5px] font-bold shadow-gold inline-flex items-center justify-center gap-2">
                    <x-ui.icon name="check" :size="17" /> Done
                </button>
            </div>

            <div class="flex justify-center">
                <button type="button" x-on:click="openSearch()"
                    class="press h-12 px-6 rounded-full bg-white/[.14] backdrop-blur ring-1 ring-white/25 text-white text-[14px] font-semibold inline-flex items-center gap-2.5 hover:bg-white/20">
                    <x-ui.icon name="search" :size="18" /> Search by HUID, code or name
                </button>
            </div>
        </div>
    </div>

    {{-- Search: slides up over the camera. A pick is handed over exactly like a scan. --}}
    <div x-show="searching" x-cloak class="absolute inset-0 z-20 flex flex-col justify-end" x-transition.opacity>
        <div class="absolute inset-0 bg-black/60" x-on:click="closeSearch()"></div>
        <div class="relative w-full max-w-[560px] mx-auto bg-ink ink-grain rounded-t-[22px] shadow-modal flex flex-col max-h-[88%] pb-[max(1rem,env(safe-area-inset-bottom))]"
             x-show="searching" x-transition:enter="transition ease-silk duration-250" x-transition:enter-start="translate-y-8 opacity-0" x-transition:enter-end="translate-y-0 opacity-100">
            <div class="h-[3px] gold-sheen rounded-t-[22px] shrink-0"></div>
            <div class="flex items-center gap-3 px-4 pt-4 pb-3 shrink-0">
                <div class="flex-1 min-w-0">
                    <div class="font-display text-[20px] leading-tight font-semibold text-gold-light">Search</div>
                    <div class="text-[12px] text-white/60">Pieces, packets and boxes. Tap one to use it.</div>
                </div>
                <button type="button" x-on:click="closeSearch()" aria-label="Back to the camera" class="press w-10 h-10 rounded-xl text-white/80 hover:bg-white/10 flex items-center justify-center">
                    <x-ui.icon name="x" :size="20" />
                </button>
            </div>
            <form x-on:submit.prevent="submitSearch()" class="px-4 pb-3 shrink-0">
                <div class="relative">
                    <x-ui.icon name="search" :size="16" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-white/45 pointer-events-none" />
                    <input type="text" x-ref="searchInput" x-model="query" x-on:input.debounce.250ms="runSearch()" autocomplete="off" autocapitalize="characters" enterkeyhint="search"
                        placeholder="HUID, code, category, packet or box"
                        class="w-full h-12 pl-10 pr-3 rounded-xl bg-white/[.08] border border-white/15 text-white text-[15px] placeholder:text-white/40
                               focus:outline-none focus:border-gold-light focus:ring-4 focus:ring-gold/20">
                </div>
            </form>
            <div class="overflow-y-auto px-2 min-h-[80px]">
                <button type="button" x-show="query.trim().length > 0" x-on:click="submitSearch()"
                    class="w-full text-left px-3 py-3 rounded-xl hover:bg-white/10 flex items-center gap-3">
                    <span class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center shrink-0"><x-ui.icon name="keyboard" :size="16" class="text-white/70" /></span>
                    <span class="min-w-0"><span class="block text-[12px] text-white/55">Use what you typed as the code</span><span class="block font-mono tracking-wide text-[14px] truncate" x-text="query.trim()"></span></span>
                </button>
                <template x-for="r in matches" :key="r.type + r.code">
                    <button type="button" x-on:click="pick(r)" class="w-full text-left px-3 py-3 rounded-xl hover:bg-white/10 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center shrink-0 text-gold-light">
                            <x-ui.icon name="gem" :size="16" x-show="r.type === 'item'" />
                            <x-ui.icon name="package" :size="16" x-show="r.type === 'packet'" />
                            <x-ui.icon name="archive" :size="16" x-show="r.type === 'box'" />
                        </span>
                        <span class="min-w-0 flex-1"><span class="block font-mono tracking-wide text-[14px] truncate" x-text="r.label"></span><span class="block text-[12.5px] text-white/60 truncate" x-text="r.detail"></span></span>
                        <x-ui.icon name="plus" :size="16" class="text-white/45 shrink-0" />
                    </button>
                </template>
                <p x-show="searched && !matches.length && !loading" class="px-3 py-6 text-center text-[13px] text-white/55">Nothing found. You can use what you typed as the code.</p>
                <p x-show="loading" class="px-3 py-6 text-center text-[13px] text-white/55">Searching</p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes rj-scanline { 0%, 100% { top: 12%; } 50% { top: 86%; } }
    .rj-scanline { animation: rj-scanline 2.2s ease-in-out infinite; }
    /* The library sizes the video itself and crops frames from that size, so the preview is only ever
       scaled with a transform (see fit()), never resized, or decoding gets the wrong region. */
    #rj-scan-region video { display: block; }
    #rj-scan-region img, #rj-scan-region #qr-shaded-region { display: none !important; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rjScanner', () => {
        // Kept outside Alpine's reactive state: proxying the decoder or media objects breaks them.
        let scanner = null;
        let audio = null;
        let last = { code: null, at: 0 };

        return {
            open: false, status: 'idle', error: '', title: 'Scan a code',
            target: null, submit: false, continuous: false, mode: 'fill',
            results: [], flash: false, lastCode: '',
            // The typed search that sits over the camera.
            searching: false, query: '', matches: [], searched: false, loading: false, searchSeq: 0,
            torchSupported: false, torchOn: false, cameras: [], camIndex: -1,

            openWith(detail) {
                const d = (Array.isArray(detail) ? detail[0] : detail) || {};
                this.title = d.title || (d.mode === 'go' ? 'Scan anything' : 'Scan a code');
                this.target = d.target || null;
                this.submit = d.submit || false;
                this.continuous = !!d.continuous;
                this.mode = d.mode || 'fill';
                this.results = []; this.lastCode = '';
                this.searching = false; this.query = ''; this.matches = []; this.searched = false;
                last = { code: null, at: 0 };
                // Created inside the tap so mobile browsers allow the beep.
                try { audio = audio || new (window.AudioContext || window.webkitAudioContext)(); audio.resume(); } catch (e) {}
                this.open = true;
                document.documentElement.classList.add('overflow-hidden');
                this.$nextTick(() => this.start());
            },

            async start() {
                this.status = 'starting'; this.error = '';
                if (!window.isSecureContext) return this.fail('insecure');
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return this.fail('unsupported');
                try {
                    const lib = await window.rjLoadScanner();
                    if (!this.open) return;
                    const F = lib.Html5QrcodeSupportedFormats;
                    scanner = new lib.Html5Qrcode('rj-scan-region', {
                        verbose: false,
                        formatsToSupport: [F.QR_CODE, F.CODE_128, F.CODE_39, F.CODE_93, F.EAN_13, F.EAN_8, F.UPC_A, F.UPC_E, F.ITF, F.CODABAR, F.DATA_MATRIX],
                        experimentalFeatures: { useBarCodeDetectorIfSupported: true },
                    });
                    const camera = this.camIndex >= 0 && this.cameras[this.camIndex] ? this.cameras[this.camIndex].id : { facingMode: 'environment' };
                    // Ask for HD: left alone, phones give 640 x 480, too coarse for the thin bars of a small
                    // tag's barcode (QR survives it, 1D barcodes don't). "ideal" never stops a camera starting.
                    const videoConstraints = Object.assign(
                        typeof camera === 'string' ? { deviceId: { exact: camera } } : camera,
                        { width: { ideal: 1920 }, height: { ideal: 1080 } });
                    await scanner.start(camera, { fps: 12, disableFlip: true, videoConstraints }, text => this.found(text), () => {});
                    if (!this.open) return this.stop();
                    this.status = 'scanning';
                    this.fit();
                    const video = document.querySelector('#rj-scan-region video');
                    if (video) { video.addEventListener('loadedmetadata', () => this.fit()); video.addEventListener('resize', () => this.fit()); }
                    setTimeout(() => this.fit(), 500);
                    try { this.torchSupported = scanner.getRunningTrackCameraCapabilities().torchFeature().isSupported(); } catch (e) { this.torchSupported = false; }
                    if (!this.cameras.length) {
                        try { this.cameras = (await lib.Html5Qrcode.getCameras()).map(c => ({ id: c.id, label: c.label })); } catch (e) {}
                    }
                } catch (e) {
                    const msg = String((e && (e.name || e.message)) || e);
                    this.fail(/NotAllowed|Permission/i.test(msg) ? 'denied' : /NotFound|Overconstrained|no camera/i.test(msg) ? 'nocamera' : 'other');
                }
            },

            // Scale the preview up until it covers the viewfinder (like a phone camera app).
            fit() {
                const vp = this.$refs.viewport, region = document.getElementById('rj-scan-region');
                if (!vp || !region) return;
                // offset* is the layout size, unaffected by the current transform.
                const w = region.offsetWidth, h = region.offsetHeight;
                if (!w || !h) return;
                const scale = Math.max(vp.clientWidth / w, vp.clientHeight / h, 1);
                region.style.transform = 'scale(' + scale + ')';
            },

            fail(kind) {
                this.status = 'error';
                this.error = {
                    insecure: 'The camera only works on a secure connection. Open the ERP with https:// and try again.',
                    unsupported: 'This browser can not use the camera. Try Chrome or Safari, or use Search.',
                    denied: 'Camera permission is blocked. Allow the camera for this site in the browser settings, then try again.',
                    nocamera: 'No camera was found on this device. Use Search instead.',
                    other: 'The camera could not start. Close any other app using it and try again.',
                }[kind];
            },

            // fromSearch: a pick the person made on purpose, so it counts even if the camera just read the same code.
            found(text, fromSearch = false) {
                if (this.searching && !fromSearch) return; // the camera keeps running under the search: ignore what it sees meanwhile
                let code = String(text || '').trim();
                // A QR sticker holds a link (…/stock/q/CODE). A field wants the code in it, except the top-bar scan, which opens the link.
                if (this.mode !== 'go') code = code.replace(/^.*\/q\/([A-Za-z0-9]+)\/?$/, '$1');
                if (!code) return;
                const now = Date.now();
                // The camera reads the same tag many times a second; count it once.
                if (!fromSearch && code === last.code && now - last.at < 2500) return;
                last = { code, at: now };
                this.feedback(code);
                this.deliver(code);
                if (this.continuous) {
                    this.results.unshift({ code, at: now });
                    this.results = this.results.slice(0, 40);
                } else {
                    setTimeout(() => this.close(), 250);
                }
            },

            openSearch() {
                this.searching = true; this.query = ''; this.matches = []; this.searched = false;
                this.$nextTick(() => this.$refs.searchInput && this.$refs.searchInput.focus());
            },

            closeSearch() { this.searching = false; },

            // Typing waits a moment, then asks the server. An answer that is no longer the latest question is dropped.
            async runSearch() {
                const q = this.query.trim();
                const seq = ++this.searchSeq;
                if (q.length < 2) { this.matches = []; this.searched = false; this.loading = false; return; }
                this.loading = true;
                try {
                    const r = await fetch(@js(route('stock.scan.search')) + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' });
                    const data = r.ok ? await r.json() : { results: [] };
                    if (seq !== this.searchSeq) return;
                    this.matches = data.results || [];
                } catch (e) {
                    if (seq !== this.searchSeq) return;
                    this.matches = [];
                }
                this.searched = true; this.loading = false;
            },

            // Pressing Enter: the only match, or else what was typed, taken as the code (like typing it into the field).
            submitSearch() {
                const q = this.query.trim();
                if (!q) return;
                const exact = this.matches.find(r => r.code.toLowerCase() === q.toLowerCase());
                this.pick(exact || { code: q });
            },

            pick(r) {
                this.searching = false;
                this.matches = []; this.query = '';
                this.found(r.code, true); // the same hand-over as a scan
            },

            deliver(code) {
                if (this.mode === 'go') {
                    window.location.href = @js(route('stock.scan')) + '?code=' + encodeURIComponent(code);
                    return;
                }
                const el = typeof this.target === 'string' ? document.querySelector(this.target) : this.target;
                if (!el) return;
                el.value = code;
                // 'enter' fields read their own value on Enter; everything else syncs wire:model first.
                if (this.submit !== 'enter') el.dispatchEvent(new Event('input', { bubbles: true }));
                if (this.submit === 'enter') {
                    el.dispatchEvent(new KeyboardEvent('keydown', { key: 'Enter', code: 'Enter', bubbles: true }));
                } else if (this.submit === 'form' && el.form) {
                    el.form.requestSubmit();
                }
            },

            feedback(code) {
                this.lastCode = code;
                this.flash = true;
                setTimeout(() => { this.flash = false; }, 650);
                try { navigator.vibrate && navigator.vibrate(60); } catch (e) {}
                try {
                    if (!audio) return;
                    const o = audio.createOscillator(), g = audio.createGain();
                    o.type = 'sine'; o.frequency.value = 1320;
                    g.gain.setValueAtTime(0.0001, audio.currentTime);
                    g.gain.exponentialRampToValueAtTime(0.25, audio.currentTime + 0.01);
                    g.gain.exponentialRampToValueAtTime(0.0001, audio.currentTime + 0.14);
                    o.connect(g).connect(audio.destination);
                    o.start(); o.stop(audio.currentTime + 0.15);
                } catch (e) {}
            },

            async toggleTorch() {
                if (!scanner) return;
                try {
                    await scanner.getRunningTrackCameraCapabilities().torchFeature().apply(!this.torchOn);
                    this.torchOn = !this.torchOn;
                } catch (e) { this.torchSupported = false; }
            },

            async switchCamera() {
                if (this.cameras.length < 2) return;
                const current = this.camIndex >= 0 ? this.camIndex : this.cameras.findIndex(c => /back|rear|environment/i.test(c.label));
                this.camIndex = (Math.max(current, 0) + 1) % this.cameras.length;
                await this.restart();
            },

            async restart() { await this.stop(); if (this.open) this.start(); },

            async stop() {
                this.torchOn = false;
                if (!scanner) return;
                const s = scanner; scanner = null;
                try { if (s.isScanning) await s.stop(); s.clear(); } catch (e) {}
            },

            async close() {
                this.open = false;
                this.status = 'idle';
                document.documentElement.classList.remove('overflow-hidden');
                await this.stop();
                // Hand focus back on desktops; on phones that would only pop the keyboard up.
                const el = typeof this.target === 'string' ? document.querySelector(this.target) : this.target;
                if (el && this.mode !== 'go' && window.matchMedia('(pointer: fine)').matches) { try { el.focus({ preventScroll: true }); } catch (e) {} }
            },

            init() {
                // Never leave the camera running in a background tab.
                window.addEventListener('resize', () => { if (this.status === 'scanning') this.fit(); });
                document.addEventListener('visibilitychange', () => { if (document.hidden && this.open) this.close(); });
            },
        };
    });
});
</script>
