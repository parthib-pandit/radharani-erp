<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Scan Stock — Mobile — Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a,button{color:inherit;text-decoration:none;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
button{font-family:inherit;cursor:pointer;}
.hidden{display:none !important;}
</style>
</head>
<body>
<div style="width:390px;max-width:100%;height:844px;box-sizing:border-box;display:flex;flex-direction:column;background:#FAF8F4;overflow:hidden;position:relative;margin:0 auto;">

  <!-- TOP BAR -->
  <div style="height:56px;min-height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 16px;box-sizing:border-box;">
    <a href="{{ route('wireframes.main') }}" style="width:34px;height:34px;border-radius:8px;background:#fff;border:1px solid #E7E0D4;display:flex;align-items:center;justify-content:center;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
    </a>
    <div class="rj-serif" style="font-size:16px;color:#211D19;">Scan Stock</div>
    <div style="width:34px;height:34px;border-radius:8px;background:#fff;border:1px solid #E7E0D4;display:flex;align-items:center;justify-content:center;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3h6l1.5 3H18a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h1.5z"/><circle cx="12" cy="13" r="3.4"/></svg>
    </div>
  </div>

  <!-- SCAN VIEW -->
  <div id="scanView" style="flex-grow:1;display:flex;flex-direction:column;padding:8px 20px 20px;box-sizing:border-box;">
    <div style="flex-grow:1;background:#1C1815;border-radius:18px;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center;min-height:0;">
      <div style="position:relative;width:216px;height:216px;">
        <div style="position:absolute;top:0;left:0;width:34px;height:34px;border-top:3px solid #C79A52;border-left:3px solid #C79A52;border-radius:6px 0 0 0;"></div>
        <div style="position:absolute;top:0;right:0;width:34px;height:34px;border-top:3px solid #C79A52;border-right:3px solid #C79A52;border-radius:0 6px 0 0;"></div>
        <div style="position:absolute;bottom:0;left:0;width:34px;height:34px;border-bottom:3px solid #C79A52;border-left:3px solid #C79A52;border-radius:0 0 0 6px;"></div>
        <div style="position:absolute;bottom:0;right:0;width:34px;height:34px;border-bottom:3px solid #C79A52;border-right:3px solid #C79A52;border-radius:0 0 6px 0;"></div>
        <div style="position:absolute;left:8px;right:8px;top:50%;height:2px;background:#C79A52;opacity:0.85;"></div>
      </div>
      <button type="button" id="scanBtn" style="position:absolute;bottom:18px;left:50%;transform:translateX(-50%);background:#A9772F;color:#FFF7EC;font-size:12.5px;font-weight:600;padding:11px 22px;border:none;border-radius:24px;">Tap to Simulate Scan</button>
    </div>

    <div style="text-align:center;margin-top:20px;">
      <div style="font-size:14.5px;color:#211D19;font-weight:600;">Point your camera at the QR code or barcode</div>
      <div style="font-size:12.5px;color:#8B7F6F;margin-top:4px;">Works for an individual item, a packet, or a full box</div>
    </div>

    <div style="display:flex;gap:10px;justify-content:center;margin-top:16px;">
      <div style="display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #E7E0D4;border-radius:20px;padding:7px 13px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9"><circle cx="12" cy="14" r="6.2"/><path d="M8.6 8.2 10 4h4l1.4 4.2"/></svg>
        <span style="font-size:11.5px;color:#55504A;">Item</span>
      </div>
      <div style="display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #E7E0D4;border-radius:20px;padding:7px 13px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
        <span style="font-size:11.5px;color:#55504A;">Packet</span>
      </div>
      <div style="display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #E7E0D4;border-radius:20px;padding:7px 13px;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9"><path d="M12 3.5l7.5 4.2v8.6L12 20.5l-7.5-4.2V7.7z"/></svg>
        <span style="font-size:11.5px;color:#55504A;">Box</span>
      </div>
    </div>

    <button type="button" style="margin-top:18px;background:none;border:none;text-align:center;font-size:13px;color:#A9772F;font-weight:700;padding:8px;">Enter ID Manually</button>
  </div>

  <!-- RESULT: ITEM FOUND -->
  <div id="foundView" class="hidden" style="flex-grow:1;display:flex;flex-direction:column;padding:8px 20px 20px;box-sizing:border-box;overflow:auto;">
    <div style="display:flex;align-items:center;gap:8px;color:#3F6B4A;background:#E4EDE3;border-radius:10px;padding:10px 14px;">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3F6B4A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>
      <span style="font-size:13px;font-weight:700;">Item Found</span>
    </div>

    <div style="background:#fff;border:1px solid #E7E0D4;border-radius:14px;padding:20px;margin-top:14px;">
      <div style="display:flex;align-items:center;justify-content:center;height:110px;background:#F3EFE8;border-radius:8px;margin-bottom:16px;">
        <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="1.3"><circle cx="12" cy="14" r="6.2"/><path d="M8.6 8.2 10 4h4l1.4 4.2"/></svg>
      </div>
      <div class="rj-serif" style="font-size:20px;color:#211D19;">Gold Ring</div>
      <div style="font-size:12.5px;color:#A9772F;font-weight:700;margin-top:2px;">RJ-G-10234</div>

      <div style="display:flex;gap:20px;margin-top:14px;">
        <div><div style="font-size:10.5px;color:#9A8F80;">HUID</div><div style="font-size:12.5px;color:#211D19;font-weight:600;margin-top:2px;">123456789</div></div>
        <div><div style="font-size:10.5px;color:#9A8F80;">Weight</div><div style="font-size:12.5px;color:#211D19;font-weight:600;margin-top:2px;">5.82g</div></div>
        <div><div style="font-size:10.5px;color:#9A8F80;">Purity</div><div style="font-size:12.5px;color:#211D19;font-weight:600;margin-top:2px;">22K</div></div>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding-top:14px;border-top:1px solid #F3EFE8;">
        <span style="font-size:12px;color:#8B7F6F;">Current Location</span>
        <span style="font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;background:#E4EDE3;color:#3F6B4A;">VAULT</span>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:10px;margin-top:16px;">
      <a href="{{ route('wireframes.move-stock') }}" style="display:flex;align-items:center;justify-content:center;gap:8px;background:#A9772F;color:#FFF7EC;font-size:14px;font-weight:700;padding:14px;border-radius:12px;">Move Stock</a>
      <a href="{{ route('wireframes.item-detail') }}" style="display:flex;align-items:center;justify-content:center;gap:8px;background:#fff;border:1px solid #E7E0D4;color:#211D19;font-size:14px;font-weight:600;padding:14px;border-radius:12px;">View Details</a>
      <a href="{{ route('wireframes.history') }}" style="display:flex;align-items:center;justify-content:center;gap:8px;background:#fff;border:1px solid #E7E0D4;color:#211D19;font-size:14px;font-weight:600;padding:14px;border-radius:12px;">View History</a>
    </div>

    <button type="button" id="resetBtn" style="margin-top:14px;background:none;border:none;text-align:center;font-size:12.5px;color:#8B7F6F;font-weight:600;padding:6px;">Scan Another Item</button>
  </div>

  <!-- BOTTOM NAV -->
  <div style="height:70px;min-height:70px;border-top:1px solid #E7E0D4;background:#fff;display:flex;align-items:center;justify-content:space-around;box-sizing:border-box;">
    <div style="display:flex;flex-direction:column;align-items:center;gap:3px;color:#A9772F;">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 9V6a2 2 0 0 1 2-2h3M20 9V6a2 2 0 0 1-2-2h-3M4 15v3a2 2 0 0 0 2 2h3M20 15v3a2 2 0 0 1-2 2h-3"/><rect x="9.5" y="9.5" width="5" height="5"/></svg>
      <span style="font-size:10px;font-weight:700;">Scan</span>
    </div>
    <a href="{{ route('wireframes.move-stock') }}" style="display:flex;flex-direction:column;align-items:center;gap:3px;color:#9A8F80;">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h14M13 6l6 6-6 6"/></svg>
      <span style="font-size:10px;">Move</span>
    </a>
    <a href="{{ route('wireframes.history') }}" style="display:flex;flex-direction:column;align-items:center;gap:3px;color:#9A8F80;">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"/><path d="M12 7.5v4.7l3.2 1.9"/></svg>
      <span style="font-size:10px;">History</span>
    </a>
    <a href="{{ route('wireframes.main') }}" style="display:flex;flex-direction:column;align-items:center;gap:3px;color:#9A8F80;">
      <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="#9A8F80" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11.5 12 4l8 7.5"/><path d="M6 10v9h5v-5h2v5h5v-9"/></svg>
      <span style="font-size:10px;">Home</span>
    </a>
  </div>
</div>
<script>
(function(){
  var scanView = document.getElementById('scanView');
  var foundView = document.getElementById('foundView');
  document.getElementById('scanBtn').addEventListener('click', function(){
    scanView.classList.add('hidden');
    foundView.classList.remove('hidden');
  });
  document.getElementById('resetBtn').addEventListener('click', function(){
    foundView.classList.add('hidden');
    scanView.classList.remove('hidden');
  });
})();
</script>
</body>
</html>
