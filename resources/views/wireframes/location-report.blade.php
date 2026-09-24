<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stock Location Report — Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a{color:inherit;text-decoration:none;}
button{font-family:inherit;cursor:pointer;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-navlink:hover{background:#241F1A !important;}
.rj-card{background:#FFFFFF;border:1px solid #E7E0D4;border-radius:10px;}
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
      <a href="{{ route('wireframes.location-report') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;background:#2A241E;color:#F5F1EA;font-size:13.5px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 19.5V10M12 19.5V5M19 19.5v-7.5"/><path d="M3.3 19.5h17.4"/></svg>
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
    <div style="height:66px;min-height:66px;border-bottom:1px solid #E7E0D4;display:flex;align-items:center;justify-content:space-between;padding:0 32px;box-sizing:border-box;background:#FFFFFF;">
      <div class="rj-serif" style="font-size:21px;color:#211D19;">Stock Location Report</div>
      <div style="display:flex;align-items:center;gap:10px;">
        <button type="button" style="display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;font-weight:600;padding:9px 15px;border-radius:8px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#3F6B4A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="3" width="16" height="18" rx="1.5"/><path d="M8 8h3M13 8h3M8 12h3M13 12h3M8 16h3M13 16h3"/></svg>
          Export Excel
        </button>
        <button type="button" style="display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;font-weight:600;padding:9px 15px;border-radius:8px;">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#A23B3B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="3" width="14" height="18" rx="1.5"/><path d="M9 9h6M9 13h6M9 17h3"/></svg>
          Export PDF
        </button>
      </div>
    </div>

    <div style="flex-grow:1;overflow:auto;padding:26px 32px;box-sizing:border-box;display:flex;flex-direction:column;gap:20px;">

      <div style="font-size:12.5px;color:#8B7F6F;">As of Thursday, 18 September 2026, 2:45 PM · 1,248 items across 6 locations</div>

      <!-- CURRENT STOCK BY LOCATION -->
      <div class="rj-card" style="padding:24px 28px;box-sizing:border-box;">
        <div style="font-size:15px;font-weight:700;color:#211D19;margin-bottom:18px;">Current Stock by Location</div>
        <div style="display:flex;align-items:center;gap:18px;padding:11px 0;border-top:1px solid #F3EFE8;">
          <div style="width:150px;font-size:13.5px;font-weight:600;color:#211D19;">Vault</div>
          <div style="flex-grow:1;height:10px;background:#F3EFE8;border-radius:5px;overflow:hidden;">
            <div style="height:100%;background:#6B4A1A;width:82.2%;border-radius:5px;"></div>
          </div>
          <div style="width:80px;text-align:right;font-size:13px;color:#211D19;font-weight:700;">1,026</div>
          <div style="width:60px;text-align:right;font-size:12px;color:#9A8F80;">82.2%</div>
        </div>
        <div style="display:flex;align-items:center;gap:18px;padding:11px 0;border-top:1px solid #F3EFE8;">
          <div style="width:150px;font-size:13.5px;font-weight:600;color:#211D19;">Counter</div>
          <div style="flex-grow:1;height:10px;background:#F3EFE8;border-radius:5px;overflow:hidden;">
            <div style="height:100%;background:#A9772F;width:11.5%;border-radius:5px;"></div>
          </div>
          <div style="width:80px;text-align:right;font-size:13px;color:#211D19;font-weight:700;">143</div>
          <div style="width:60px;text-align:right;font-size:12px;color:#9A8F80;">11.5%</div>
        </div>
        <div style="display:flex;align-items:center;gap:18px;padding:11px 0;border-top:1px solid #F3EFE8;">
          <div style="width:150px;font-size:13.5px;font-weight:600;color:#211D19;">Karigar</div>
          <div style="flex-grow:1;height:10px;background:#F3EFE8;border-radius:5px;overflow:hidden;">
            <div style="height:100%;background:#C79A52;width:3.4%;border-radius:5px;"></div>
          </div>
          <div style="width:80px;text-align:right;font-size:13px;color:#211D19;font-weight:700;">42</div>
          <div style="width:60px;text-align:right;font-size:12px;color:#9A8F80;">3.4%</div>
        </div>
        <div style="display:flex;align-items:center;gap:18px;padding:11px 0;border-top:1px solid #F3EFE8;">
          <div style="width:150px;font-size:13.5px;font-weight:600;color:#211D19;">Hallmarking</div>
          <div style="flex-grow:1;height:10px;background:#F3EFE8;border-radius:5px;overflow:hidden;">
            <div style="height:100%;background:#D9B87A;width:1.4%;border-radius:5px;"></div>
          </div>
          <div style="width:80px;text-align:right;font-size:13px;color:#211D19;font-weight:700;">18</div>
          <div style="width:60px;text-align:right;font-size:12px;color:#9A8F80;">1.4%</div>
        </div>
        <div style="display:flex;align-items:center;gap:18px;padding:11px 0;border-top:1px solid #F3EFE8;">
          <div style="width:150px;font-size:13.5px;font-weight:600;color:#211D19;">Photography</div>
          <div style="flex-grow:1;height:10px;background:#F3EFE8;border-radius:5px;overflow:hidden;">
            <div style="height:100%;background:#E7D2A6;width:0.9%;border-radius:5px;"></div>
          </div>
          <div style="width:80px;text-align:right;font-size:13px;color:#211D19;font-weight:700;">11</div>
          <div style="width:60px;text-align:right;font-size:12px;color:#9A8F80;">0.9%</div>
        </div>
        <div style="display:flex;align-items:center;gap:18px;padding:11px 0;border-top:1px solid #F3EFE8;">
          <div style="width:150px;font-size:13.5px;font-weight:600;color:#211D19;">Other</div>
          <div style="flex-grow:1;height:10px;background:#F3EFE8;border-radius:5px;overflow:hidden;">
            <div style="height:100%;background:#F0E2C4;width:0.6%;border-radius:5px;"></div>
          </div>
          <div style="width:80px;text-align:right;font-size:13px;color:#211D19;font-weight:700;">8</div>
          <div style="width:60px;text-align:right;font-size:12px;color:#9A8F80;">0.6%</div>
        </div>
      </div>

      <div style="display:flex;gap:20px;flex-grow:1;min-height:0;">

        <!-- EXPECTED BACK -->
        <div class="rj-card" style="flex-grow:1;padding:24px 26px;box-sizing:border-box;">
          <div style="display:flex;align-items:center;gap:9px;margin-bottom:16px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M12 7.5v4.7l3.2 1.9"/></svg>
            <span style="font-size:14.5px;font-weight:700;color:#211D19;">Items Expected Back</span>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-top:1px solid #F3EFE8;">
            <div>
              <div style="font-size:13.5px;font-weight:600;color:#211D19;">Karigar</div>
              <div style="font-size:11.5px;color:#9A8F80;margin-top:2px;">Expected 20 Sep 2026</div>
            </div>
            <span style="font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px;background:#F5E9D2;color:#96631B;">7 items</span>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-top:1px solid #F3EFE8;">
            <div>
              <div style="font-size:13.5px;font-weight:600;color:#211D19;">Hallmarking</div>
              <div style="font-size:11.5px;color:#9A8F80;margin-top:2px;">Expected 19 Sep 2026</div>
            </div>
            <span style="font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px;background:#F5E9D2;color:#96631B;">4 items</span>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 0;border-top:1px solid #F3EFE8;">
            <div>
              <div style="font-size:13.5px;font-weight:600;color:#211D19;">Photography</div>
              <div style="font-size:11.5px;color:#9A8F80;margin-top:2px;">Expected 21 Sep 2026</div>
            </div>
            <span style="font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px;background:#F5E9D2;color:#96631B;">3 items</span>
          </div>
        </div>

        <!-- OVERDUE -->
        <div class="rj-card" style="flex-grow:1;padding:24px 26px;box-sizing:border-box;border-color:#E7C7C0;">
          <div style="display:flex;align-items:center;gap:9px;margin-bottom:16px;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#A23B3B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5l9.5 16.5H2.5z"/><path d="M12 10v4.2M12 17v.01"/></svg>
            <span style="font-size:14.5px;font-weight:700;color:#211D19;">Overdue Returns</span>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 14px;border-radius:9px;background:#F9EEEC;margin-top:10px;">
            <div>
              <div style="font-size:13.5px;font-weight:600;color:#211D19;">Karigar — Ramesh Kumar</div>
              <div style="font-size:11.5px;color:#A23B3B;margin-top:2px;font-weight:600;">2 days overdue</div>
            </div>
            <span style="font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px;background:#F6E2E0;color:#A23B3B;">2 items</span>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:13px 14px;border-radius:9px;background:#F9EEEC;margin-top:10px;">
            <div>
              <div style="font-size:13.5px;font-weight:600;color:#211D19;">Photography</div>
              <div style="font-size:11.5px;color:#A23B3B;margin-top:2px;font-weight:600;">1 day overdue</div>
            </div>
            <span style="font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px;background:#F6E2E0;color:#A23B3B;">1 items</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
