<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Box &amp; Packet Management — BOX-007 — Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a{color:inherit;text-decoration:none;}
button{font-family:inherit;cursor:pointer;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-navlink:hover{background:#241F1A !important;}
.rj-card{background:#FFFFFF;border:1px solid #E7E0D4;border-radius:10px;}
.rj-actbtn{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:8px;border:1px solid #E7E0D4;background:#fff;font-size:12.5px;font-weight:600;color:#211D19;width:100%;box-sizing:border-box;text-align:left;}
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
      <a href="{{ route('wireframes.box-packet') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;background:#2A241E;color:#F5F1EA;font-size:13.5px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5l7.5 4.2v8.6L12 20.5l-7.5-4.2V7.7z"/><path d="M4.5 7.7 12 12l7.5-4.3M12 12v8.5"/></svg>
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
        <div style="font-size:12.5px;color:#F0EAE0;font-weight:600;">EchoCrew</div>
        <div style="font-size:10.5px;color:#8B7F6F;">Owner</div>
      </div>
    </div>
  </div>

  <!-- MAIN -->
  <div style="flex-grow:1;min-width:0;height:100%;display:flex;flex-direction:column;box-sizing:border-box;">
    <div style="height:66px;min-height:66px;border-bottom:1px solid #E7E0D4;display:flex;align-items:center;justify-content:space-between;padding:0 32px;box-sizing:border-box;background:#FFFFFF;">
      <div class="rj-serif" style="font-size:21px;color:#211D19;">Box &amp; Packet Management</div>
      <div style="display:flex;align-items:center;gap:8px;">
        <span style="padding:8px 14px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">BOX-004</span>
        <span style="padding:8px 14px;border-radius:20px;background:#1C1815;color:#F5F1EA;font-size:12.5px;font-weight:700;">BOX-007</span>
        <span style="padding:8px 14px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">BOX-009</span>
        <span style="padding:8px 14px;border-radius:20px;background:#fff;border:1px solid #E7E0D4;color:#55504A;font-size:12.5px;">BOX-012</span>
      </div>
    </div>

    <div style="flex-grow:1;overflow:auto;padding:26px 32px;box-sizing:border-box;display:flex;gap:22px;">

      <!-- LEFT -->
      <div style="width:300px;flex-shrink:0;display:flex;flex-direction:column;gap:16px;">
        <div class="rj-card" style="padding:22px;box-sizing:border-box;">
          <div style="font-size:11px;color:#9A8F80;text-transform:uppercase;letter-spacing:0.05em;">Box</div>
          <div class="rj-serif" style="font-size:26px;color:#211D19;margin-top:3px;">BOX-007</div>
          <div style="display:flex;gap:18px;margin-top:14px;">
            <div><div class="rj-serif" style="font-size:20px;color:#8A5F22;">3</div><div style="font-size:11px;color:#9A8F80;">Packets</div></div>
            <div><div class="rj-serif" style="font-size:20px;color:#8A5F22;">37</div><div style="font-size:11px;color:#9A8F80;">Items</div></div>
          </div>
          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:14px;border-top:1px solid #F3EFE8;">
            <span style="font-size:12px;color:#8B7F6F;">Location</span>
            <span style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">VAULT</span>
          </div>
        </div>

        <div class="rj-card" style="padding:20px;box-sizing:border-box;display:flex;flex-direction:column;align-items:center;gap:10px;">
          <div style="font-size:12px;font-weight:600;color:#211D19;align-self:flex-start;">Box QR Code</div>
          <div id="qrGridBox" style="width:140px;height:140px;background:#fff;border:1px solid #E7E0D4;border-radius:8px;padding:8px;box-sizing:border-box;display:grid;grid-template-columns:repeat(15,1fr);grid-template-rows:repeat(15,1fr);"></div>
          <div style="font-size:11.5px;color:#55504A;font-weight:600;">BOX-007</div>
        </div>

        <div class="rj-card" style="padding:14px;box-sizing:border-box;display:flex;flex-direction:column;gap:8px;">
          <button type="button" class="rj-actbtn"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="2" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Assign Item</button>
          <a href="{{ route('wireframes.move-stock') }}" class="rj-actbtn"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>Move Packet</a>
          <a href="{{ route('wireframes.move-stock') }}" class="rj-actbtn"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5l7.5 4.2v8.6L12 20.5l-7.5-4.2V7.7z"/></svg>Move Box</a>
          <a href="{{ route('wireframes.history') }}" class="rj-actbtn"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><path d="M12 7.5v4.7l3.2 1.9"/></svg>View History</a>
        </div>
      </div>

      <!-- RIGHT: HIERARCHY -->
      <div class="rj-card" style="flex-grow:1;padding:26px 30px;box-sizing:border-box;overflow:auto;">
        <div style="font-size:14px;font-weight:700;color:#211D19;margin-bottom:2px;">Contents Hierarchy</div>
        <div style="font-size:11.5px;color:#9A8F80;margin-bottom:20px;">Box → Packet → Item</div>

        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:34px;height:34px;border-radius:8px;background:#1C1815;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#C79A52" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3.5l7.5 4.2v8.6L12 20.5l-7.5-4.2V7.7z"/></svg>
          </div>
          <span class="rj-serif" style="font-size:17px;color:#211D19;">BOX-007</span>
          <span style="font-size:11.5px;color:#9A8F80;">Vault Shelf 4</span>
        </div>

        <div style="margin-left:17px;border-left:1.5px solid #EAE3D6;padding-left:26px;margin-top:6px;">

          <div style="margin-top:18px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:28px;height:28px;border-radius:7px;background:#F3EFE8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
              </div>
              <span style="font-size:14px;font-weight:700;color:#211D19;">PKT-018</span>
              <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:#F3EFE8;color:#75695B;">3 items</span>
              <a href="{{ route('wireframes.item-detail') }}" style="margin-left:auto;font-size:11.5px;color:#A9772F;font-weight:600;">View packet →</a>
            </div>
            <div style="margin-left:14px;border-left:1.5px solid #F0EBE0;padding-left:24px;margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;">
              <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;gap:7px;background:#FAF8F4;border:1px solid #EFEAE1;border-radius:20px;padding:6px 12px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#C79A52;display:inline-block;"></span>
                <span style="font-size:11.5px;color:#55504A;font-weight:600;">RJ-G-10234</span>
                <span style="font-size:11px;color:#9A8F80;">5.82g</span>
              </a>
              <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;gap:7px;background:#FAF8F4;border:1px solid #EFEAE1;border-radius:20px;padding:6px 12px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#C79A52;display:inline-block;"></span>
                <span style="font-size:11.5px;color:#55504A;font-weight:600;">RJ-G-10235</span>
                <span style="font-size:11px;color:#9A8F80;">12.40g</span>
              </a>
              <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;gap:7px;background:#FAF8F4;border:1px solid #EFEAE1;border-radius:20px;padding:6px 12px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#C79A52;display:inline-block;"></span>
                <span style="font-size:11.5px;color:#55504A;font-weight:600;">RJ-G-10236</span>
                <span style="font-size:11px;color:#9A8F80;">18.20g</span>
              </a>
            </div>
          </div>

          <div style="margin-top:18px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:28px;height:28px;border-radius:7px;background:#F3EFE8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
              </div>
              <span style="font-size:14px;font-weight:700;color:#211D19;">PKT-019</span>
              <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:#F3EFE8;color:#75695B;">2 items</span>
              <a href="{{ route('wireframes.item-detail') }}" style="margin-left:auto;font-size:11.5px;color:#A9772F;font-weight:600;">View packet →</a>
            </div>
            <div style="margin-left:14px;border-left:1.5px solid #F0EBE0;padding-left:24px;margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;">
              <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;gap:7px;background:#FAF8F4;border:1px solid #EFEAE1;border-radius:20px;padding:6px 12px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#C79A52;display:inline-block;"></span>
                <span style="font-size:11.5px;color:#55504A;font-weight:600;">RJ-G-10237</span>
                <span style="font-size:11px;color:#9A8F80;">4.10g</span>
              </a>
              <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;gap:7px;background:#FAF8F4;border:1px solid #EFEAE1;border-radius:20px;padding:6px 12px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#C79A52;display:inline-block;"></span>
                <span style="font-size:11.5px;color:#55504A;font-weight:600;">RJ-G-10238</span>
                <span style="font-size:11px;color:#9A8F80;">9.65g</span>
              </a>
            </div>
          </div>

          <div style="margin-top:18px;">
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:28px;height:28px;border-radius:7px;background:#F3EFE8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
              </div>
              <span style="font-size:14px;font-weight:700;color:#211D19;">PKT-020</span>
              <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:#F3EFE8;color:#75695B;">2 items</span>
              <a href="{{ route('wireframes.item-detail') }}" style="margin-left:auto;font-size:11.5px;color:#A9772F;font-weight:600;">View packet →</a>
            </div>
            <div style="margin-left:14px;border-left:1.5px solid #F0EBE0;padding-left:24px;margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;">
              <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;gap:7px;background:#FAF8F4;border:1px solid #EFEAE1;border-radius:20px;padding:6px 12px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#C79A52;display:inline-block;"></span>
                <span style="font-size:11.5px;color:#55504A;font-weight:600;">RJ-G-10239</span>
                <span style="font-size:11px;color:#9A8F80;">6.30g</span>
              </a>
              <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;gap:7px;background:#FAF8F4;border:1px solid #EFEAE1;border-radius:20px;padding:6px 12px;">
                <span style="width:5px;height:5px;border-radius:50%;background:#C79A52;display:inline-block;"></span>
                <span style="font-size:11.5px;color:#55504A;font-weight:600;">RJ-G-10240</span>
                <span style="font-size:11px;color:#9A8F80;">11.05g</span>
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  function buildQr(size) {
    var cells = [];
    function inFinder(x, y, fx, fy) { return x >= fx && x < fx + 7 && y >= fy && y < fy + 7; }
    for (var y = 0; y < size; y++) {
      for (var x = 0; x < size; x++) {
        var on;
        if (inFinder(x, y, 0, 0) || inFinder(x, y, size - 7, 0) || inFinder(x, y, 0, size - 7)) {
          var fx = inFinder(x, y, 0, 0) ? 0 : (size - 7);
          var fy = inFinder(x, y, 0, 0) ? 0 : (inFinder(x, y, size - 7, 0) ? 0 : (size - 7));
          var rx = x - fx, ry = y - fy;
          on = rx === 0 || rx === 6 || ry === 0 || ry === 6 || (rx >= 2 && rx <= 4 && ry >= 2 && ry <= 4);
        } else {
          on = ((x * 11 + y * 5 + (x ^ y) * 3) % 5) < 2;
        }
        cells.push(on ? '#1C1815' : '#FFFFFF');
      }
    }
    return cells;
  }
  var grid = document.getElementById('qrGridBox');
  var cells = buildQr(15);
  for (var i = 0; i < cells.length; i++) {
    var cell = document.createElement('div');
    cell.style.background = cells[i];
    grid.appendChild(cell);
  }
})();
</script>
</body>
</html>
