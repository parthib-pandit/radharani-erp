<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>External Stock Movement — Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a{color:inherit;text-decoration:none;}
button{font-family:inherit;cursor:pointer;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-navlink:hover{background:#241F1A !important;}
.rj-card{background:#FFFFFF;border:1px solid #E7E0D4;border-radius:10px;}
.rj-label{font-size:11.5px;color:#8B7F6F;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;margin-bottom:7px;display:block;}
.rj-input{width:100%;box-sizing:border-box;border:1px solid #E7E0D4;border-radius:8px;padding:11px 13px;font-size:13.5px;color:#211D19;background:#fff;}
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
      <a href="{{ route('wireframes.external-movement') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;background:#2A241E;color:#F5F1EA;font-size:13.5px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12.5 20 4l-6.5 16-3-6.5z"/></svg>
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
      <div class="rj-serif" style="font-size:21px;color:#211D19;">External Stock Movement</div>
      <div style="display:flex;align-items:center;gap:8px;background:#F3EFE8;border-radius:8px;padding:8px 14px;">
        <span style="font-size:13px;color:#211D19;font-weight:700;">VAULT</span>
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg>
        <span style="font-size:13px;color:#211D19;font-weight:700;">HALLMARKING CENTRE</span>
      </div>
    </div>

    <div style="flex-grow:1;overflow:auto;padding:26px 32px;box-sizing:border-box;display:flex;gap:22px;">

      <!-- LEFT -->
      <div style="width:400px;flex-shrink:0;">
        <div class="rj-card" style="padding:20px;box-sizing:border-box;">
          <div style="font-size:14px;font-weight:700;color:#211D19;margin-bottom:6px;">Purpose</div>
          <div style="font-size:11.5px;color:#9A8F80;margin-bottom:14px;">Choose why this stock is leaving the vault</div>
          <div style="display:flex;flex-direction:column;gap:9px;">
            <div style="display:flex;align-items:center;gap:12px;padding:13px 14px;border-radius:9px;border:1.5px solid #A9772F;background:#FBF3E6;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8A5F22" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M9 12l2 2 4-4.5"/></svg>
              <span style="font-size:13px;font-weight:700;color:#8A5F22;">Hallmarking</span>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:13px 14px;border-radius:9px;border:1px solid #E7E0D4;background:#fff;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="12.5" rx="2"/><path d="M8 7l1.4-2.3h5.2L16 7"/><circle cx="12" cy="13" r="3.2"/></svg>
              <span style="font-size:13px;color:#55504A;">Photography</span>
            </div>
            <div style="display:flex;align-items:center;gap:12px;padding:13px 14px;border-radius:9px;border:1px solid #E7E0D4;background:#fff;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h5l2 2h9v10.5H4z"/></svg>
              <span style="font-size:13px;color:#55504A;">Other Purpose</span>
            </div>
            <button type="button" style="display:flex;align-items:center;gap:8px;padding:10px 14px;border-radius:9px;border:1px dashed #D8CEBC;background:#FAF8F4;color:#8A5F22;font-size:12px;font-weight:600;">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#8A5F22" stroke-width="2.2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
              Add a Purpose (Admin)
            </button>
          </div>
        </div>

        <div class="rj-card" style="padding:20px;margin-top:16px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <div style="font-size:14px;font-weight:700;color:#211D19;">Items</div>
            <span style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;background:#F5E9D2;color:#96631B;">2 items</span>
          </div>
          <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-top:1px solid #F3EFE8;">
            <div style="width:36px;height:36px;border-radius:8px;background:#F3EFE8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="1.5"><path d="M5 8.5l7-4 7 4v7l-7 4-7-4z"/></svg>
            </div>
            <div style="flex-grow:1;">
              <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10302</div>
              <div style="font-size:12px;color:#55504A;">Gold Necklace · 22.60g</div>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:12px;padding:12px 0;border-top:1px solid #F3EFE8;">
            <div style="width:36px;height:36px;border-radius:8px;background:#F3EFE8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="1.5"><path d="M5 8.5l7-4 7 4v7l-7 4-7-4z"/></svg>
            </div>
            <div style="flex-grow:1;">
              <div style="font-size:12.5px;color:#A9772F;font-weight:700;">RJ-G-10236</div>
              <div style="font-size:12px;color:#55504A;">Gold Necklace · 18.20g</div>
            </div>
          </div>
          <button type="button" style="width:100%;margin-top:10px;padding:10px;border-radius:8px;border:1px dashed #D8CEBC;background:#FAF8F4;color:#8A5F22;font-size:12.5px;font-weight:600;display:flex;align-items:center;justify-content:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#8A5F22" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V6a2 2 0 0 1 2-2h3M20 9V6a2 2 0 0 1-2-2h-3M4 15v3a2 2 0 0 0 2 2h3M20 15v3a2 2 0 0 1-2 2h-3"/><rect x="9.5" y="9.5" width="5" height="5"/></svg>
            Select / Scan Items
          </button>
        </div>
      </div>

      <!-- RIGHT -->
      <div class="rj-card" style="flex-grow:1;padding:26px 30px;box-sizing:border-box;overflow:auto;">
        <div>
          <span class="rj-label">Destination</span>
          <div class="rj-input" style="display:flex;align-items:center;justify-content:space-between;">
            <span>BIS Hallmarking Centre — MG Road</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px 22px;margin-top:20px;">
          <div>
            <span class="rj-label">Dispatch Date</span>
            <div class="rj-input" style="display:flex;align-items:center;gap:9px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.7"><rect x="4" y="6" width="16" height="14" rx="1.5"/><path d="M4 10h16M8 4v4M16 4v4"/></svg>
              <span>18 Sep 2026</span>
            </div>
          </div>
          <div>
            <span class="rj-label">Expected Return</span>
            <div class="rj-input" style="display:flex;align-items:center;gap:9px;">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.7"><rect x="4" y="6" width="16" height="14" rx="1.5"/><path d="M4 10h16M8 4v4M16 4v4"/></svg>
              <span>19 Sep 2026</span>
            </div>
          </div>
          <div>
            <span class="rj-label">Responsible Staff</span>
            <div class="rj-input" style="display:flex;align-items:center;justify-content:space-between;">
              <span>Amit Shah</span>
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
            </div>
          </div>
          <div>
            <span class="rj-label">Reference No.</span>
            <div class="rj-input" style="color:#9A8F80;">HM-2026-0914</div>
          </div>
        </div>

        <div style="margin-top:20px;">
          <span class="rj-label">Remarks</span>
          <div class="rj-input" style="min-height:56px;color:#9A8F80;">Monthly hallmarking batch — 2 necklaces, BIS certification pending.</div>
        </div>

        <div style="margin-top:20px;">
          <span class="rj-label">Upload Photos / Documents</span>
          <div style="border:1.5px dashed #D8CEBC;border-radius:10px;padding:26px 16px;text-align:center;background:#FAF8F4;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 8px;display:block;"><path d="M12 15V4M8 8l4-4 4 4"/><path d="M4 15v3.5a1.5 1.5 0 0 0 1.5 1.5h13a1.5 1.5 0 0 0 1.5-1.5V15"/></svg>
            <div style="font-size:12.5px;font-weight:700;color:#211D19;">Drop files or tap to upload</div>
            <div style="font-size:11px;color:#9A8F80;margin-top:3px;">Photos, BIS receipt, or any supporting document</div>
          </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:flex-end;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid #F3EFE8;">
          <button type="button" style="padding:12px 20px;border-radius:8px;border:1px solid #E7E0D4;background:#fff;color:#55504A;font-size:13px;font-weight:600;">Cancel</button>
          <button type="button" style="padding:12px 26px;border-radius:8px;border:none;background:#A9772F;color:#FFF7EC;font-size:13.5px;font-weight:700;">Confirm Dispatch</button>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
