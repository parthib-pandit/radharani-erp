<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stock Overview — Radharani Jewellery Works</title>
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
<div style="width:1440px;max-width:100%;height:1080px;box-sizing:border-box;display:flex;background:#FAF8F4;overflow:hidden;margin:0 auto;">

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
      <a href="{{ route('wireframes.main') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;background:#2A241E;color:#F5F1EA;font-size:13.5px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11.5 12 4l8 7.5"/><path d="M6 10v9h5v-5h2v5h5v-9"/></svg>
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
      <a href="{{ route('wireframes.logbook') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="5.5" y="4" width="13" height="16.5" rx="1.5"/><rect x="8.5" y="2.3" width="7" height="3" rx="1"/><path d="M8.5 10.2h7M8.5 13.4h7M8.5 16.6h4.5"/></svg>
        Logbook
      </a>
      <a href="{{ route('wireframes.location-report') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19.5V10M12 19.5V5M19 19.5v-7.5"/><path d="M3.3 19.5h17.4"/></svg>
        Reports
      </a>
    </div>

    <div style="margin-top:auto;padding:16px 20px;border-top:1px solid #2A241E;display:flex;align-items:center;gap:10px;">
      <div style="width:30px;height:30px;border-radius:50%;background:#3A322A;display:flex;align-items:center;justify-content:center;color:#D9CEBE;font-size:12px;font-weight:600;">EC</div>
      <div>
        <div style="font-size:12.5px;color:#F0EAE0;font-weight:600;">Echocrew</div>
        <div style="font-size:10.5px;color:#8B7F6F;">Owner</div>
      </div>
    </div>
  </div>

  <!-- MAIN -->
  <div style="flex-grow:1;min-width:0;height:100%;display:flex;flex-direction:column;box-sizing:border-box;">

    <!-- TOPBAR -->
    <div style="height:66px;min-height:66px;border-bottom:1px solid #E7E0D4;display:flex;align-items:center;justify-content:space-between;padding:0 32px;box-sizing:border-box;background:#FFFFFF;">
      <div>
        <div class="rj-serif" style="font-size:21px;color:#211D19;">Stock Overview</div>
      </div>
      <div style="display:flex;align-items:center;gap:14px;">
        <div style="display:flex;align-items:center;gap:8px;background:#F3EFE8;border-radius:8px;padding:8px 12px;width:260px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.8" stroke-linecap="round"><circle cx="10" cy="10" r="6"/><path d="M15 15l5 5"/></svg>
          <span style="font-size:12.5px;color:#9A8F80;">Search item, packet, box…</span>
        </div>
        <button type="button" style="width:36px;height:36px;border-radius:8px;border:1px solid #E7E0D4;background:#fff;display:flex;align-items:center;justify-content:center;position:relative;">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 10a6 6 0 0 1 12 0c0 4 1.4 5.4 1.4 5.4H4.6S6 14 6 10z"/><path d="M10 18.6a2 2 0 0 0 4 0"/></svg>
          <span style="position:absolute;top:6px;right:7px;width:7px;height:7px;border-radius:50%;background:#A23B3B;border:1.5px solid #fff;"></span>
        </button>
        <a href="{{ route('wireframes.scan-stock') }}" style="display:flex;align-items:center;gap:8px;background:#A9772F;color:#FFF7EC;font-size:13px;font-weight:600;padding:10px 18px;border-radius:8px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFF7EC" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V6a2 2 0 0 1 2-2h3M20 9V6a2 2 0 0 1-2-2h-3M4 15v3a2 2 0 0 0 2 2h3M20 15v3a2 2 0 0 1-2 2h-3"/><rect x="9.5" y="9.5" width="5" height="5"/></svg>
          Scan Stock
        </a>
      </div>
    </div>

    <!-- CONTENT -->
    <div style="flex-grow:1;overflow:hidden;padding:26px 32px 0;box-sizing:border-box;display:flex;flex-direction:column;gap:22px;">

      <div style="font-size:12.5px;color:#8B7F6F;">Thursday, 18 September 2026 · Last synced 2:45 PM</div>

      <!-- HERO ROW -->
      <div style="display:flex;gap:20px;">
        <div class="rj-card" style="width:300px;padding:22px 24px;box-sizing:border-box;flex-shrink:0;background:#1C1815;border:none;">
          <div style="font-size:12px;color:#A79C8D;letter-spacing:0.04em;">Total Items in System</div>
          <div class="rj-serif" style="font-size:44px;color:#F5F1EA;margin-top:6px;line-height:1;">1,248</div>
          <div style="display:flex;gap:16px;margin-top:16px;">
            <div>
              <div style="font-size:11px;color:#8B7F6F;">Sold Today</div>
              <div style="font-size:15px;color:#E9C989;font-weight:600;margin-top:2px;">7</div>
            </div>
            <div>
              <div style="font-size:11px;color:#8B7F6F;">Moved Today</div>
              <div style="font-size:15px;color:#E9C989;font-weight:600;margin-top:2px;">19</div>
            </div>
          </div>
        </div>

        <div class="rj-card" style="flex-grow:1;padding:22px 24px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="font-size:13px;font-weight:600;color:#211D19;">Current Stock Distribution</div>
            <a href="{{ route('wireframes.location-report') }}" style="font-size:12px;color:#A9772F;font-weight:600;">View full report →</a>
          </div>
          <div style="display:flex;height:22px;border-radius:5px;overflow:hidden;margin-top:16px;">
            <div style="flex-grow:82.2;background:#6B4A1A;"></div>
            <div style="flex-grow:11.5;background:#A9772F;"></div>
            <div style="flex-grow:3.4;background:#C79A52;"></div>
            <div style="flex-grow:1.4;background:#D9B87A;"></div>
            <div style="flex-grow:0.9;background:#E7D2A6;"></div>
            <div style="flex-grow:0.6;background:#F0E2C4;"></div>
          </div>
          <div style="display:flex;gap:22px;margin-top:14px;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:9px;height:9px;border-radius:2px;background:#6B4A1A;display:inline-block;"></span><span style="font-size:12px;color:#55504A;">Vault</span><span style="font-size:12px;color:#211D19;font-weight:600;">1,026</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:9px;height:9px;border-radius:2px;background:#A9772F;display:inline-block;"></span><span style="font-size:12px;color:#55504A;">Counter</span><span style="font-size:12px;color:#211D19;font-weight:600;">143</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:9px;height:9px;border-radius:2px;background:#C79A52;display:inline-block;"></span><span style="font-size:12px;color:#55504A;">Karigar</span><span style="font-size:12px;color:#211D19;font-weight:600;">42</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:9px;height:9px;border-radius:2px;background:#D9B87A;display:inline-block;"></span><span style="font-size:12px;color:#55504A;">Hallmarking</span><span style="font-size:12px;color:#211D19;font-weight:600;">18</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:9px;height:9px;border-radius:2px;background:#E7D2A6;display:inline-block;"></span><span style="font-size:12px;color:#55504A;">Photography</span><span style="font-size:12px;color:#211D19;font-weight:600;">11</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:9px;height:9px;border-radius:2px;background:#F0E2C4;display:inline-block;"></span><span style="font-size:12px;color:#55504A;">Other</span><span style="font-size:12px;color:#211D19;font-weight:600;">8</span></div>
          </div>
        </div>
      </div>

      <!-- LOCATION TILES -->
      <div style="display:grid;grid-template-columns:repeat(6,1fr);gap:14px;">
        <div class="rj-card" style="padding:16px 16px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;"><span style="font-size:11.5px;color:#8B7F6F;">Vault</span><span style="width:7px;height:7px;border-radius:2px;background:#6B4A1A;display:inline-block;"></span></div>
          <div class="rj-serif" style="font-size:26px;color:#211D19;margin-top:6px;">1,026</div>
          <div style="font-size:11px;color:#9A8F80;margin-top:2px;">items</div>
        </div>
        <div class="rj-card" style="padding:16px 16px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;"><span style="font-size:11.5px;color:#8B7F6F;">Counter</span><span style="width:7px;height:7px;border-radius:2px;background:#A9772F;display:inline-block;"></span></div>
          <div class="rj-serif" style="font-size:26px;color:#211D19;margin-top:6px;">143</div>
          <div style="font-size:11px;color:#9A8F80;margin-top:2px;">items</div>
        </div>
        <div class="rj-card" style="padding:16px 16px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;"><span style="font-size:11.5px;color:#8B7F6F;">With Karigar</span><span style="width:7px;height:7px;border-radius:2px;background:#C79A52;display:inline-block;"></span></div>
          <div class="rj-serif" style="font-size:26px;color:#211D19;margin-top:6px;">42</div>
          <div style="font-size:11px;color:#9A8F80;margin-top:2px;">items</div>
        </div>
        <div class="rj-card" style="padding:16px 16px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;"><span style="font-size:11.5px;color:#8B7F6F;">At Hallmarking</span><span style="width:7px;height:7px;border-radius:2px;background:#D9B87A;display:inline-block;"></span></div>
          <div class="rj-serif" style="font-size:26px;color:#211D19;margin-top:6px;">18</div>
          <div style="font-size:11px;color:#9A8F80;margin-top:2px;">items</div>
        </div>
        <div class="rj-card" style="padding:16px 16px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;"><span style="font-size:11.5px;color:#8B7F6F;">Photography</span><span style="width:7px;height:7px;border-radius:2px;background:#E7D2A6;display:inline-block;"></span></div>
          <div class="rj-serif" style="font-size:26px;color:#211D19;margin-top:6px;">11</div>
          <div style="font-size:11px;color:#9A8F80;margin-top:2px;">items</div>
        </div>
        <div class="rj-card" style="padding:16px 16px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;"><span style="font-size:11.5px;color:#8B7F6F;">Other Purpose</span><span style="width:7px;height:7px;border-radius:2px;background:#F0E2C4;display:inline-block;"></span></div>
          <div class="rj-serif" style="font-size:26px;color:#211D19;margin-top:6px;">8</div>
          <div style="font-size:11px;color:#9A8F80;margin-top:2px;">items</div>
        </div>
      </div>

      <!-- RECENT MOVEMENTS -->
      <div class="rj-card" style="flex-grow:1;min-height:0;padding:0;box-sizing:border-box;display:flex;flex-direction:column;overflow:hidden;">
        <div style="padding:16px 22px;border-bottom:1px solid #EFEAE1;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
          <div style="font-size:14px;font-weight:600;color:#211D19;">Recent Stock Movements</div>
          <a href="{{ route('wireframes.logbook') }}" style="font-size:12px;color:#A9772F;font-weight:600;">Open Logbook →</a>
        </div>
        <div style="overflow:auto;">
          <div style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:10px 22px;font-size:11px;color:#9A8F80;text-transform:uppercase;letter-spacing:0.05em;">
            <div>Time</div><div>Item</div><div>Product</div><div>Movement</div><div>Staff</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:12px 22px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">10:02 AM</div><div style="font-size:13px;color:#211D19;font-weight:600;">RJ-G-10234</div><div style="font-size:12.5px;color:#55504A;">Gold Ring</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;"><span style="color:#55504A;">Vault</span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg><span style="color:#211D19;font-weight:600;">Counter</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">RK</span><span style="font-size:12.5px;color:#55504A;">Rahul</span></div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:12px 22px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">10:17 AM</div><div style="font-size:13px;color:#211D19;font-weight:600;">RJ-G-10241</div><div style="font-size:12.5px;color:#55504A;">Gold Chain</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;"><span style="color:#55504A;">Vault</span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg><span style="color:#211D19;font-weight:600;">Counter</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">AS</span><span style="font-size:12.5px;color:#55504A;">Amit</span></div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:12px 22px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">11:34 AM</div><div style="font-size:13px;color:#211D19;font-weight:600;">RJ-G-10188</div><div style="font-size:12.5px;color:#55504A;">Gold Bangle</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;"><span style="color:#55504A;">Vault</span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg><span style="color:#211D19;font-weight:600;">Karigar</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">RK</span><span style="font-size:12.5px;color:#55504A;">Rahul</span></div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:12px 22px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">12:05 PM</div><div style="font-size:13px;color:#211D19;font-weight:600;">RJ-G-10302</div><div style="font-size:12.5px;color:#55504A;">Gold Necklace</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;"><span style="color:#55504A;">Vault</span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg><span style="color:#211D19;font-weight:600;">Hallmarking</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">AS</span><span style="font-size:12.5px;color:#55504A;">Amit</span></div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:12px 22px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">12:40 PM</div><div style="font-size:13px;color:#211D19;font-weight:600;">RJ-G-10256</div><div style="font-size:12.5px;color:#55504A;">Gold Earring</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;"><span style="color:#55504A;">Vault</span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg><span style="color:#211D19;font-weight:600;">Photography</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">PS</span><span style="font-size:12.5px;color:#55504A;">Priya</span></div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:12px 22px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">1:20 PM</div><div style="font-size:13px;color:#211D19;font-weight:600;">RJ-G-10199</div><div style="font-size:12.5px;color:#55504A;">Gold Bangle</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;"><span style="color:#55504A;">Karigar</span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg><span style="color:#211D19;font-weight:600;">Vault</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">RK</span><span style="font-size:12.5px;color:#55504A;">Rahul</span></div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:100px 1fr 1fr 220px 130px;padding:12px 22px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#8B7F6F;">2:10 PM</div><div style="font-size:13px;color:#211D19;font-weight:600;">RJ-S-10099</div><div style="font-size:12.5px;color:#55504A;">Silver Ring</div>
            <div style="display:flex;align-items:center;gap:7px;font-size:12.5px;"><span style="color:#55504A;">Counter</span><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg><span style="color:#211D19;font-weight:600;">Vault</span></div>
            <div style="display:flex;align-items:center;gap:7px;"><span style="width:20px;height:20px;border-radius:50%;background:#F3EFE8;color:#75695B;font-size:9.5px;font-weight:700;display:flex;align-items:center;justify-content:center;">PS</span><span style="font-size:12.5px;color:#55504A;">Priya</span></div>
          </div>
        </div>
      </div>
    </div>
    <div style="height:20px;flex-shrink:0;"></div>
  </div>
</div>
</body>
</html>
