<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Item, Packet &amp; Box History — Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a,button{color:inherit;text-decoration:none;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-navlink:hover{background:#241F1A !important;}
.rj-card{background:#FFFFFF;border:1px solid #E7E0D4;border-radius:10px;}
.rj-row:hover{background:#FAF7F1;}
button{font-family:inherit;cursor:pointer;}
.hidden{display:none !important;}
.rj-tab{padding:12px 18px;font-size:13px;font-weight:700;border:none;background:none;border-bottom:2.5px solid transparent;color:#9A8F80;}
.rj-tab.active{border-bottom-color:#A9772F;color:#211D19;}
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
      <a href="{{ route('wireframes.history') }}" class="rj-navlink" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:7px;background:#2A241E;color:#F5F1EA;font-size:13.5px;font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><path d="M12 7.5v4.7l3.2 1.9"/></svg>
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
    <div style="height:66px;min-height:66px;border-bottom:1px solid #E7E0D4;display:flex;align-items:center;justify-content:space-between;padding:0 32px;box-sizing:border-box;background:#FFFFFF;">
      <div class="rj-serif" style="font-size:21px;color:#211D19;">History &amp; Audit Trail</div>
      <div style="font-size:12px;color:#9A8F80;">Every movement, permanently recorded</div>
    </div>

    <div style="flex-grow:1;overflow:auto;padding:26px 32px;box-sizing:border-box;">

      <!-- TABS -->
      <div style="display:flex;gap:6px;border-bottom:1px solid #E7E0D4;">
        <button type="button" id="tabProduct" class="rj-tab" data-tab="product">Product History</button>
        <button type="button" id="tabPacket" class="rj-tab" data-tab="packet">Packet History</button>
        <button type="button" id="tabBox" class="rj-tab active" data-tab="box">Box History</button>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:20px;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:10px;">
          <span style="font-size:12px;color:#9A8F80;">Viewing</span>
          <span class="rj-serif" id="refLabel" style="font-size:19px;color:#211D19;">BOX-007</span>
        </div>
        <button type="button" style="font-size:12.5px;color:#A9772F;font-weight:600;background:none;border:none;">Change reference →</button>
      </div>

      <div class="rj-card" style="padding:0;box-sizing:border-box;overflow:hidden;">
        <div style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:12px 24px;font-size:10.5px;color:#9A8F80;text-transform:uppercase;letter-spacing:0.05em;border-bottom:1px solid #EFEAE1;">
          <div>Date</div><div>Time</div><div>From</div><div>To</div><div>Staff</div><div>Purpose</div><div>Reference</div>
        </div>

        <div id="rowsProduct" class="hidden">
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">20 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">5:30 PM</div>
            <div style="font-size:12.5px;color:#55504A;">Karigar</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="font-size:12.5px;color:#55504A;">Amit</div>
            <div style="font-size:12.5px;color:#55504A;">Received back</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10075</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">18 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">11:34 AM</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Karigar</div>
            <div style="font-size:12.5px;color:#55504A;">Amit</div>
            <div style="font-size:12.5px;color:#55504A;">Repair</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10029</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">17 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">7:42 PM</div>
            <div style="font-size:12.5px;color:#55504A;">Counter</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="font-size:12.5px;color:#55504A;">Rahul</div>
            <div style="font-size:12.5px;color:#55504A;">End of day</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10015</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">17 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">10:02 AM</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Counter</div>
            <div style="font-size:12.5px;color:#55504A;">Rahul</div>
            <div style="font-size:12.5px;color:#55504A;">Display</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10002</div>
          </div>
        </div>

        <div id="rowsPacket" class="hidden">
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">17 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">7:45 PM</div>
            <div style="font-size:12.5px;color:#55504A;">Counter</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="font-size:12.5px;color:#55504A;">Rahul</div>
            <div style="font-size:12.5px;color:#55504A;">End of day</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10038</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">17 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">10:00 AM</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Counter</div>
            <div style="font-size:12.5px;color:#55504A;">Rahul</div>
            <div style="font-size:12.5px;color:#55504A;">Display</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10020</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">16 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">9:00 AM</div>
            <div style="font-size:12.5px;color:#55504A;">Registered</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="font-size:12.5px;color:#55504A;">Amit</div>
            <div style="font-size:12.5px;color:#55504A;">Packet created</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10001</div>
          </div>
        </div>

        <div id="rowsBox">
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">20 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">6:40 PM</div>
            <div style="font-size:12.5px;color:#55504A;">Counter</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="font-size:12.5px;color:#55504A;">Priya</div>
            <div style="font-size:12.5px;color:#55504A;">End of day</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10080</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">19 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">2:15 PM</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Counter</div>
            <div style="font-size:12.5px;color:#55504A;">Priya</div>
            <div style="font-size:12.5px;color:#55504A;">Display</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10068</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">19 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">9:10 AM</div>
            <div style="font-size:12.5px;color:#55504A;">Hallmarking</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="font-size:12.5px;color:#55504A;">Amit</div>
            <div style="font-size:12.5px;color:#55504A;">Received back</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10061</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">18 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">11:20 AM</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Hallmarking</div>
            <div style="font-size:12.5px;color:#55504A;">Amit</div>
            <div style="font-size:12.5px;color:#55504A;">Hallmarking</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10052</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">17 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">7:45 PM</div>
            <div style="font-size:12.5px;color:#55504A;">Counter</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Vault</div>
            <div style="font-size:12.5px;color:#55504A;">Rahul</div>
            <div style="font-size:12.5px;color:#55504A;">End of day</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10037</div>
          </div>
          <div class="rj-row" style="display:grid;grid-template-columns:110px 90px 130px 130px 120px 1fr 130px;padding:14px 24px;border-top:1px solid #F3EFE8;align-items:center;">
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">17 Sep 2026</div>
            <div style="font-size:12.5px;color:#8B7F6F;">10:00 AM</div>
            <div style="font-size:12.5px;color:#55504A;">Vault</div>
            <div style="font-size:12.5px;color:#211D19;font-weight:600;">Counter</div>
            <div style="font-size:12.5px;color:#55504A;">Rahul</div>
            <div style="font-size:12.5px;color:#55504A;">Display</div>
            <div style="font-size:11.5px;color:#9A8F80;font-family:monospace;">MOV-10021</div>
          </div>
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:9px;margin-top:18px;">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="10" width="14" height="9" rx="1.5"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
        <span style="font-size:12px;color:#9A8F80;">These records are locked once created — historical entries cannot be silently edited or deleted.</span>
      </div>
    </div>
  </div>
</div>
<script>
(function(){
  var tabs = {
    product: { btn: document.getElementById('tabProduct'), rows: document.getElementById('rowsProduct'), label: 'RJ-G-10234 · Gold Ring' },
    packet: { btn: document.getElementById('tabPacket'), rows: document.getElementById('rowsPacket'), label: 'PKT-018' },
    box: { btn: document.getElementById('tabBox'), rows: document.getElementById('rowsBox'), label: 'BOX-007' }
  };
  var refLabel = document.getElementById('refLabel');

  function setTab(name) {
    Object.keys(tabs).forEach(function(key){
      var t = tabs[key];
      var on = key === name;
      t.btn.classList.toggle('active', on);
      t.rows.classList.toggle('hidden', !on);
    });
    refLabel.textContent = tabs[name].label;
  }

  Object.keys(tabs).forEach(function(key){
    tabs[key].btn.addEventListener('click', function(){ setTab(key); });
  });

  setTab('box');
})();
</script>
</body>
</html>
