<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Move Stock — Mobile — Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a,button{color:inherit;text-decoration:none;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
button{font-family:inherit;cursor:pointer;}
input,textarea{font-family:inherit;}
.dest-btn{display:flex;flex-direction:row;align-items:center;gap:10px;padding:16px;border-radius:12px;text-align:left;}
</style>
</head>
<body>
<div style="width:390px;max-width:100%;height:844px;box-sizing:border-box;display:flex;flex-direction:column;background:#FAF8F4;overflow:hidden;position:relative;margin:0 auto;">

  <div style="height:56px;min-height:56px;display:flex;align-items:center;justify-content:space-between;padding:0 16px;box-sizing:border-box;">
    <a href="{{ route('wireframes.scan-stock') }}" style="width:34px;height:34px;border-radius:8px;background:#fff;border:1px solid #E7E0D4;display:flex;align-items:center;justify-content:center;">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#55504A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
    </a>
    <div class="rj-serif" style="font-size:16px;color:#211D19;">Move Stock</div>
    <div style="width:34px;"></div>
  </div>

  <div style="flex-grow:1;overflow:auto;padding:6px 20px 100px;box-sizing:border-box;">

    <div style="background:#fff;border:1px solid #E7E0D4;border-radius:14px;padding:16px;display:flex;align-items:center;gap:14px;">
      <div style="width:52px;height:52px;border-radius:8px;background:#F3EFE8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#B8AE9E" stroke-width="1.4"><circle cx="12" cy="14" r="6.2"/><path d="M8.6 8.2 10 4h4l1.4 4.2"/></svg>
      </div>
      <div style="flex-grow:1;min-width:0;">
        <div class="rj-serif" style="font-size:16.5px;color:#211D19;">Gold Ring</div>
        <div style="font-size:12px;color:#8B7F6F;margin-top:2px;">RJ-G-10234 · 5.82g · HUID 123456789</div>
      </div>
    </div>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-top:16px;padding:12px 14px;background:#F3EFE8;border-radius:10px;">
      <span style="font-size:12px;color:#75695B;">Current Location</span>
      <span style="font-size:12.5px;font-weight:700;color:#211D19;">VAULT</span>
    </div>

    <div style="font-size:13.5px;font-weight:700;color:#211D19;margin-top:22px;margin-bottom:10px;">Move To</div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
      <button type="button" class="dest-btn" data-dest="Counter" style="grid-column:span 1;border:1.5px solid #A9772F;background:#FBF3E6;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#A9772F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="10" rx="1.2"/><path d="M3 8l2-4h14l2 4"/></svg>
        <span style="font-size:13px;font-weight:700;color:#8A5F22;">Counter</span>
      </button>
      <button type="button" class="dest-btn" data-dest="Karigar" style="grid-column:span 1;border:1.5px solid #E7E0D4;background:#FFFFFF;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 3v2.2M12 18.8V21M4.9 7.4l1.9 1.1M17.2 15.5l1.9 1.1M4.9 16.6l1.9-1.1M17.2 8.5l1.9-1.1M3 12h2.2M18.8 12H21"/></svg>
        <span style="font-size:13px;font-weight:700;color:#211D19;">Karigar</span>
      </button>
      <button type="button" class="dest-btn" data-dest="Hallmarking" style="grid-column:span 1;border:1.5px solid #E7E0D4;background:#FFFFFF;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M9 12l2 2 4-4.5"/></svg>
        <span style="font-size:13px;font-weight:700;color:#211D19;">Hallmarking</span>
      </button>
      <button type="button" class="dest-btn" data-dest="Photography" style="grid-column:span 1;border:1.5px solid #E7E0D4;background:#FFFFFF;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="12.5" rx="2"/><path d="M8 7l1.4-2.3h5.2L16 7"/><circle cx="12" cy="13" r="3.2"/></svg>
        <span style="font-size:13px;font-weight:700;color:#211D19;">Photography</span>
      </button>
      <button type="button" class="dest-btn" data-dest="Other Purpose" style="grid-column:span 2;border:1.5px solid #E7E0D4;background:#FFFFFF;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8B7F6F" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h5l2 2h9v10.5H4z"/></svg>
        <span style="font-size:13px;font-weight:700;color:#211D19;">Other Purpose</span>
      </button>
    </div>

    <div id="confirmPanel" style="background:#fff;border:1px solid #E7E0D4;border-radius:14px;padding:18px;margin-top:20px;">
      <div style="display:flex;justify-content:space-between;padding-bottom:12px;border-bottom:1px solid #F3EFE8;">
        <span style="font-size:12px;color:#8B7F6F;">Destination</span>
        <span id="selectedLabel" style="font-size:13px;font-weight:700;color:#A9772F;">Counter</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #F3EFE8;">
        <span style="font-size:12px;color:#8B7F6F;">Staff</span>
        <span style="font-size:13px;font-weight:600;color:#211D19;">Rahul Kumar</span>
      </div>
      <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #F3EFE8;">
        <span style="font-size:12px;color:#8B7F6F;">Date &amp; Time</span>
        <span style="font-size:13px;font-weight:600;color:#211D19;">17 Sep 2026, 10:02 AM</span>
      </div>
      <div style="padding-top:12px;">
        <span style="font-size:12px;color:#8B7F6F;">Remarks</span>
        <div style="margin-top:8px;background:#F9F7F2;border:1px solid #EFEAE1;border-radius:8px;padding:10px 12px;font-size:12.5px;color:#55504A;">Morning counter display</div>
      </div>
    </div>
  </div>

  <div style="position:absolute;left:0;right:0;bottom:0;padding:16px 20px 22px;background:linear-gradient(180deg,rgba(250,248,244,0) 0%,#FAF8F4 30%);box-sizing:border-box;">
    <button type="button" style="width:100%;background:#A9772F;color:#FFF7EC;font-size:15px;font-weight:700;padding:16px;border:none;border-radius:12px;">Confirm Movement</button>
  </div>
</div>
<script>
(function(){
  var buttons = Array.prototype.slice.call(document.querySelectorAll('.dest-btn'));
  var selectedLabel = document.getElementById('selectedLabel');

  function select(dest) {
    buttons.forEach(function(btn){
      var on = btn.getAttribute('data-dest') === dest;
      var icon = btn.querySelector('svg');
      var label = btn.querySelector('span');
      btn.style.border = on ? '1.5px solid #A9772F' : '1.5px solid #E7E0D4';
      btn.style.background = on ? '#FBF3E6' : '#FFFFFF';
      icon.setAttribute('stroke', on ? '#A9772F' : '#8B7F6F');
      label.style.color = on ? '#8A5F22' : '#211D19';
    });
    selectedLabel.textContent = dest;
  }

  buttons.forEach(function(btn){
    btn.addEventListener('click', function(){
      select(btn.getAttribute('data-dest'));
    });
  });

  select('Counter');
})();
</script>
</body>
</html>
