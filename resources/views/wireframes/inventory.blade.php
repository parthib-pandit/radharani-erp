<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inventory — Radharani Jewellery Works</title>
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
.rj-chip{display:flex;align-items:center;gap:6px;background:#F3EFE8;border:1px solid #E7E0D4;border-radius:7px;padding:7px 11px;font-size:12px;color:#55504A;white-space:nowrap;}
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
      <a href="{{ route('wireframes.main') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;color:#C9C0B3;font-size:13.5px;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11.5 12 4l8 7.5"/><path d="M6 10v9h5v-5h2v5h5v-9"/></svg>
        Overview
      </a>
      <a href="{{ route('wireframes.inventory') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;background:#2A241E;color:#F5F1EA;font-size:13.5px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="5" width="4.5" height="4.5"/><rect x="4" y="14.5" width="4.5" height="4.5"/><path d="M11.5 7h8.5M11.5 16.7h8.5"/></svg>
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
      <div class="rj-serif" style="font-size:21px;color:#211D19;">Inventory</div>
      <div style="display:flex;align-items:center;gap:12px;">
        <button type="button" style="display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;font-weight:600;padding:9px 15px;border-radius:8px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v12M8 12l4 4 4-4"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg>
          Export
        </button>
        <a href="{{ route('wireframes.scan-stock') }}" style="display:flex;align-items:center;gap:8px;background:#A9772F;color:#FFF7EC;font-size:13px;font-weight:600;padding:10px 18px;border-radius:8px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#FFF7EC" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V6a2 2 0 0 1 2-2h3M20 9V6a2 2 0 0 1-2-2h-3M4 15v3a2 2 0 0 0 2 2h3M20 15v3a2 2 0 0 1-2 2h-3"/><rect x="9.5" y="9.5" width="5" height="5"/></svg>
          Scan Stock
        </a>
      </div>
    </div>

    <div style="flex-grow:1;overflow:hidden;padding:22px 32px 0;box-sizing:border-box;display:flex;flex-direction:column;gap:16px;">

      <!-- FILTER BAR -->
      <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:8px;background:#FFFFFF;border:1px solid #E7E0D4;border-radius:7px;padding:8px 13px;width:270px;box-sizing:border-box;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.8" stroke-linecap="round"><circle cx="10" cy="10" r="6"/><path d="M15 15l5 5"/></svg>
          <span style="font-size:12.5px;color:#9A8F80;">Search item ID, HUID…</span>
        </div>
        <div style="width:1px;height:20px;background:#E7E0D4;margin:0 2px;"></div>
        <div class="rj-chip">Location: <b style="color:#211D19;font-weight:600;">All</b>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div class="rj-chip">Category: <b style="color:#211D19;font-weight:600;">All</b>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div class="rj-chip">Purity: <b style="color:#211D19;font-weight:600;">All</b>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div class="rj-chip">Status: <b style="color:#211D19;font-weight:600;">All</b>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div class="rj-chip">Packet: <b style="color:#211D19;font-weight:600;">All</b>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div class="rj-chip">Box: <b style="color:#211D19;font-weight:600;">All</b>
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        <div style="margin-left:auto;font-size:12px;color:#9A8F80;">12 of 1,248 items shown</div>
      </div>

      <!-- TABLE -->
      <div class="rj-card" style="flex-grow:1;min-height:0;padding:0;box-sizing:border-box;display:flex;flex-direction:column;overflow:hidden;">
        <div style="overflow:auto;flex-grow:1;">
          <div style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:11px 20px;font-size:10.5px;color:#9A8F80;text-transform:uppercase;letter-spacing:0.05em;position:sticky;top:0;background:#fff;border-bottom:1px solid #EFEAE1;z-index:1;">
            <div>Item ID</div><div>HUID</div><div>Product</div><div>Category</div><div>Weight</div><div>Purity</div><div>Packet</div><div>Box</div><div>Location</div><div>Status</div>
          </div>

          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10234</div><div style="font-size:12px;color:#8B7F6F;">123456789</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Ring</div><div style="font-size:12.5px;color:#55504A;">Ring</div><div style="font-size:12.5px;color:#55504A;">5.82g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-018</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-007</div><div style="font-size:12px;color:#55504A;">Vault</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">Available</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10235</div><div style="font-size:12px;color:#8B7F6F;">123456790</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Bangle</div><div style="font-size:12.5px;color:#55504A;">Bangle</div><div style="font-size:12.5px;color:#55504A;">12.40g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-018</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-007</div><div style="font-size:12px;color:#55504A;">Counter</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">Available</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10236</div><div style="font-size:12px;color:#8B7F6F;">123456791</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Necklace</div><div style="font-size:12.5px;color:#55504A;">Necklace</div><div style="font-size:12.5px;color:#55504A;">18.20g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-018</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-007</div><div style="font-size:12px;color:#55504A;">Vault</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">Available</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10241</div><div style="font-size:12px;color:#8B7F6F;">123456812</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Chain</div><div style="font-size:12.5px;color:#55504A;">Necklace</div><div style="font-size:12.5px;color:#55504A;">18.40g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-019</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-007</div><div style="font-size:12px;color:#55504A;">Counter</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">Available</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10256</div><div style="font-size:12px;color:#8B7F6F;">123456830</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Bangle</div><div style="font-size:12.5px;color:#55504A;">Bangle</div><div style="font-size:12.5px;color:#55504A;">12.10g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-020</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-007</div><div style="font-size:12px;color:#55504A;">Photography</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#F5E9D2;color:#96631B;">Photography</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10188</div><div style="font-size:12px;color:#8B7F6F;">123455990</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Earring</div><div style="font-size:12.5px;color:#55504A;">Earring</div><div style="font-size:12.5px;color:#55504A;">4.10g</div><div style="font-size:12.5px;color:#55504A;">18K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-011</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-004</div><div style="font-size:12px;color:#55504A;">Karigar</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#F5E9D2;color:#96631B;">With Karigar</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10302</div><div style="font-size:12px;color:#8B7F6F;">123456901</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Necklace</div><div style="font-size:12.5px;color:#55504A;">Necklace</div><div style="font-size:12.5px;color:#55504A;">22.60g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-025</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-009</div><div style="font-size:12px;color:#55504A;">Hallmarking</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#F5E9D2;color:#96631B;">Hallmarking</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-S-10099</div><div style="font-size:12px;color:#8B7F6F;">— (Internal ID)</div><div style="font-size:13px;color:#211D19;font-weight:600;">Silver Ring</div><div style="font-size:12.5px;color:#55504A;">Ring</div><div style="font-size:12.5px;color:#55504A;">6.30g</div><div style="font-size:12.5px;color:#55504A;">925 Silver</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-030</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-012</div><div style="font-size:12px;color:#55504A;">Vault</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">Available</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10199</div><div style="font-size:12px;color:#8B7F6F;">123456745</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Bangle</div><div style="font-size:12.5px;color:#55504A;">Bangle</div><div style="font-size:12.5px;color:#55504A;">14.90g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-011</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-004</div><div style="font-size:12px;color:#55504A;">Vault</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">Available</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-S-10145</div><div style="font-size:12px;color:#8B7F6F;">— (Internal ID)</div><div style="font-size:13px;color:#211D19;font-weight:600;">Silver Bangle</div><div style="font-size:12.5px;color:#55504A;">Bangle</div><div style="font-size:12.5px;color:#55504A;">28.50g</div><div style="font-size:12.5px;color:#55504A;">925 Silver</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-031</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-012</div><div style="font-size:12px;color:#55504A;">Counter</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">Available</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10310</div><div style="font-size:12px;color:#8B7F6F;">123456955</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Ring</div><div style="font-size:12.5px;color:#55504A;">Ring</div><div style="font-size:12.5px;color:#55504A;">3.95g</div><div style="font-size:12.5px;color:#55504A;">18K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-026</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-009</div><div style="font-size:12px;color:#55504A;">Other</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#F5E9D2;color:#96631B;">Other Purpose</span></div>
          </a>
          <a href="{{ route('wireframes.item-detail') }}" class="rj-row" style="display:grid;grid-template-columns:110px 100px 1.15fr 100px 80px 90px 90px 90px 130px 130px;padding:13px 20px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10088</div><div style="font-size:12px;color:#8B7F6F;">123455301</div><div style="font-size:13px;color:#211D19;font-weight:600;">Gold Necklace</div><div style="font-size:12.5px;color:#55504A;">Necklace</div><div style="font-size:12.5px;color:#55504A;">25.10g</div><div style="font-size:12.5px;color:#55504A;">22K</div><div style="font-size:12.5px;color:#8B7F6F;">PKT-005</div><div style="font-size:12.5px;color:#8B7F6F;">BOX-002</div><div style="font-size:12px;color:#55504A;">—</div>
            <div><span style="font-size:11px;font-weight:600;padding:4px 10px;border-radius:20px;background:#EDEAE4;color:#5B5650;">Sold</span></div>
          </a>
        </div>
      </div>
    </div>
    <div style="height:20px;flex-shrink:0;"></div>
  </div>
</div>
</body>
</html>
