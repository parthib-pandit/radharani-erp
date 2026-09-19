<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stock Movement Logbook — Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a{color:inherit;text-decoration:none;}
button{font-family:inherit;cursor:pointer;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-navlink:hover{background:#241F1A !important;}
.rj-card{background:#FFFFFF;border:1px solid #E7E0D4;border-radius:10px;}
.rj-row:hover{background:#FAF7F1;}
</style>
</head>
<body>
<div style="width:1440px;max-width:100%;height:1000px;box-sizing:border-box;display:flex;background:#FAF8F4;overflow:hidden;margin:0 auto;">

  <!-- SIDEBAR -->
  <div style="width:248px;min-width:248px;height:100%;background:#1C1815;display:flex;flex-direction:column;box-sizing:border-box;">
    <div style="padding:26px 22px 18px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #2A241E;">
      <div style="width:38px;height:38px;border-radius:50%;border:1.5px solid #A9772F;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <span class="rj-serif" style="font-style:italic;font-size:18px;color:#A9772F;">R</span>
      </div>
      <div>
        <div class="rj-serif" style="font-size:15px;color:#F5F1EA;line-height:1.2;">Radharani</div>
        <div style="font-size:9.5px;letter-spacing:0.14em;color:#A79C8D;text-transform:uppercase;margin-top:2px;">Jewellery Works</div>
      </div>
    </div>
    <div style="padding:16px 12px;display:flex;flex-direction:column;gap:2px;">
      <div style="font-size:10.5px;letter-spacing:0.1em;color:#75695B;text-transform:uppercase;padding:8px 12px 6px;">Stock</div>
      <a href="{{ route('wireframes.main') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11.5 12 4l8 7.5"/><path d="M6 10v9h5v-5h2v5h5v-9"/></svg>
        Overview
      </a>
      <a href="{{ route('wireframes.inventory') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="5" width="4.5" height="4.5"/><rect x="4" y="14.5" width="4.5" height="4.5"/><path d="M11.5 7h8.5M11.5 16.7h8.5"/></svg>
        Inventory
      </a>
      <a href="{{ route('wireframes.scan-stock') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V6a2 2 0 0 1 2-2h3M20 9V6a2 2 0 0 1-2-2h-3M4 15v3a2 2 0 0 0 2 2h3M20 15v3a2 2 0 0 1-2 2h-3"/><rect x="9.5" y="9.5" width="5" height="5"/></svg>
        Scan Stock
      </a>
      <div style="font-size:10.5px;letter-spacing:0.1em;color:#75695B;text-transform:uppercase;padding:16px 12px 6px;">Movements</div>
      <a href="{{ route('wireframes.karigar-dispatch') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 3v2.2M12 18.8V21M4.9 7.4l1.9 1.1M17.2 15.5l1.9 1.1M4.9 16.6l1.9-1.1M17.2 8.5l1.9-1.1M3 12h2.2M18.8 12H21"/></svg>
        Karigar
      </a>
      <a href="{{ route('wireframes.external-movement') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 20 4l-6.5 16-3-6.5z"/></svg>
        External Movements
      </a>
      <a href="{{ route('wireframes.box-packet') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5l7.5 4.2v8.6L12 20.5l-7.5-4.2V7.7z"/><path d="M4.5 7.7 12 12l7.5-4.3M12 12v8.5"/></svg>
        Box &amp; Packets
      </a>
      <div style="font-size:10.5px;letter-spacing:0.1em;color:#75695B;text-transform:uppercase;padding:16px 12px 6px;">Records</div>
      <a href="{{ route('wireframes.history') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><path d="M12 7.5v4.7l3.2 1.9"/></svg>
        Item History
      </a>
      <a href="{{ route('wireframes.logbook') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;background:#2A241E;color:#F5F1EA;font-size:13.5px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="5.5" y="4" width="13" height="16.5" rx="1.5"/><rect x="8.5" y="2.3" width="7" height="3" rx="1"/><path d="M8.5 10.2h7M8.5 13.4h7M8.5 16.6h4.5"/></svg>
        Logbook
      </a>
      <a href="{{ route('wireframes.location-report') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19.5V10M12 19.5V5M19 19.5v-7.5"/><path d="M3.3 19.5h17.4"/></svg>
        Reports
      </a>
    </div>
    <div style="margin-top:auto;padding:16px 20px;border-top:1px solid #2A241E;display:flex;align-items:center;gap:10px;">
      <div style="width:30px;height:30px;border-radius:50%;background:#3A322A;display:flex;align-items:center;justify-content:center;color:#D9CEBE;font-size:12px;font-weight:600;">AN</div>
      <div>
        <div style="font-size:12.5px;color:#F0EAE0;font-weight:600;">Anirudha</div>
        <div style="font-size:10.5px;color:#8B7F6F;">Owner</div>
      </div>
    </div>
  </div>

  <!-- MAIN -->
  <div style="flex-grow:1;min-width:0;height:100%;display:flex;flex-direction:column;box-sizing:border-box;">
    <div style="height:66px;min-height:66px;border-bottom:1px solid #E7E0D4;display:flex;align-items:center;justify-content:space-between;padding:0 32px;box-sizing:border-box;background:#FFFFFF;">
      <div class="rj-serif" style="font-size:21px;color:#211D19;">Stock Movement Logbook</div>
      <div style="display:flex;align-items:center;gap:10px;">
        <div style="display:flex;align-items:center;gap:10px;background:#F3EFE8;border-radius:8px;padding:8px 14px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
          <span style="font-size:13px;color:#211D19;font-weight:600;">17 September 2026</span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
        </div>
        <button type="button" style="display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;font-weight:600;padding:9px 15px;border-radius:8px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v12M8 12l4 4 4-4"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
          Export
        </button>
      </div>
    </div>

    <div style="flex-grow:1;overflow:auto;padding:22px 32px 0;box-sizing:border-box;display:flex;flex-direction:column;gap:18px;">

      <div style="display:flex;gap:14px;">
        <div class="rj-card" style="flex-grow:1;padding:16px 20px;box-sizing:border-box;">
          <div style="font-size:11px;color:#9A8F80;">Movements Today</div>
          <div class="rj-serif" style="font-size:24px;color:#211D19;margin-top:4px;">34</div>
        </div>
        <div class="rj-card" style="flex-grow:1;padding:16px 20px;box-sizing:border-box;">
          <div style="font-size:11px;color:#9A8F80;">Items Moved</div>
          <div class="rj-serif" style="font-size:24px;color:#211D19;margin-top:4px;">41</div>
        </div>
        <div class="rj-card" style="flex-grow:1;padding:16px 20px;box-sizing:border-box;">
          <div style="font-size:11px;color:#9A8F80;">Active Staff</div>
          <div class="rj-serif" style="font-size:24px;color:#211D19;margin-top:4px;">3</div>
        </div>
        <div class="rj-card" style="flex-grow:1;padding:16px 20px;box-sizing:border-box;">
          <div style="font-size:11px;color:#9A8F80;">Sold Today</div>
          <div class="rj-serif" style="font-size:24px;color:#211D19;margin-top:4px;">1</div>
        </div>
      </div>

      <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <span style="padding:8px 16px;border-radius:20px;background:#1C1815;color:#F5F1EA;font-size:12.5px;font-weight:700;">All</span>
        <span style="padding:8px 16px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">Vault</span>
        <span style="padding:8px 16px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">Counter</span>
        <span style="padding:8px 16px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">Karigar</span>
        <span style="padding:8px 16px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">Hallmarking</span>
        <span style="padding:8px 16px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">Photography</span>
        <span style="padding:8px 16px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">Other</span>
      </div>

      <div class="rj-card" style="flex-grow:1;min-height:0;padding:0;box-sizing:border-box;display:flex;flex-direction:column;overflow:hidden;">
        <div style="overflow:auto;flex-grow:1;">
          <div style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:11px 24px;font-size:10.5px;color:#9A8F80;text-transform:uppercase;letter-spacing:0.05em;position:sticky;top:0;background:#fff;border-bottom:1px solid #EFEAE1;">
            <div>Time</div><div>Item</div><div>Product</div><div>From</div><div>To</div><div>Staff</div><div>Purpose</div>
          </div>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">10:02 AM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10234</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Ring</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Counter</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">RK</span>
              <span style="font-size:12.5px;color:#55504A;">Rahul</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Display</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">10:17 AM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10241</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Chain</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Counter</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">RK</span>
              <span style="font-size:12.5px;color:#55504A;">Rahul</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Display</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">11:34 AM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10188</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Earring</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Karigar</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">AS</span>
              <span style="font-size:12.5px;color:#55504A;">Amit</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Repair</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">12:05 PM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10302</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Necklace</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Hallmarking</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">AS</span>
              <span style="font-size:12.5px;color:#55504A;">Amit</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Hallmarking</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">12:40 PM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10256</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Bangle</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Photography</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">PS</span>
              <span style="font-size:12.5px;color:#55504A;">Priya</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Photoshoot</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">1:20 PM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10199</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Bangle</div>
            <div style="font-size:12.5px;color:#55504A;">Karigar</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">RK</span>
              <span style="font-size:12.5px;color:#55504A;">Rahul</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Returned</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">2:10 PM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-S-10099</div>
            <div style="font-size:12.5px;color:#55504A;">Silver Ring</div>
            <div style="font-size:12.5px;color:#55504A;">Counter</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">PS</span>
              <span style="font-size:12.5px;color:#55504A;">Priya</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">End of day</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">3:45 PM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10088</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Necklace</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Sold</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">AS</span>
              <span style="font-size:12.5px;color:#55504A;">Amit</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Sale — Invoice #4021</div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:90px 150px 1fr 130px 130px 140px 1.1fr;padding:13px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">4:30 PM</div>
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10310</div>
            <div style="font-size:12.5px;color:#55504A;">Gold Ring</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Other</div>
            <div style="display:flex;align-items:center;gap:7px;">
              <span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">PS</span>
              <span style="font-size:12.5px;color:#55504A;">Priya</span>
            </div>
            <div style="font-size:12.5px;color:#55504A;">Insurance valuation</div>
          </a>
        </div>
      </div>
    </div>
    <div style="height:20px;flex-shrink:0;"></div>
  </div>
</div>
</body>
</html>
