<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Radharani Jewellery Works</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a{color:inherit;text-decoration:none;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-card{background:#FFFFFF;border:1px solid #E7E0D4;border-radius:12px;transition:border-color .15s ease,box-shadow .15s ease,transform .15s ease;}
.rj-card:hover{border-color:#A9772F;box-shadow:0 6px 18px rgba(28,24,21,0.06);transform:translateY(-1px);}
.wrap{max-width:720px;margin:0 auto;padding:100px 24px 80px;text-align:center;}
</style>
</head>
<body>
<div class="wrap">
  <div style="width:64px;height:64px;border-radius:50%;border:1.5px solid #A9772F;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;background:#1C1815;">
    <span class="rj-serif" style="font-style:italic;font-size:30px;color:#A9772F;">R</span>
  </div>

  <div class="rj-serif" style="font-size:34px;color:#211D19;">Radharani Jewellery Works</div>
  <div style="font-size:13px;letter-spacing:.14em;color:#8B7F6F;text-transform:uppercase;margin-top:8px;">Stock Management &amp; Customer Portal</div>

  <div style="font-size:14px;color:#55504A;max-width:460px;margin:24px auto 40px;line-height:1.6;">
    Every piece tracked from vault to counter. Current gold and silver rates,
    reflected instantly across the collection.
  </div>

  <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;max-width:480px;margin:0 auto;">
    <a href="{{ route('login') }}" class="rj-card" style="padding:24px 16px;">
      <div style="font-size:15px;font-weight:700;color:#211D19;">Staff Login</div>
      <div style="font-size:11.5px;color:#8B7F6F;margin-top:6px;">Inventory, movements &amp; sales</div>
    </a>
    <a href="{{ route('portal.login') }}" class="rj-card" style="padding:24px 16px;">
      <div style="font-size:15px;font-weight:700;color:#211D19;">Customer Portal</div>
      <div style="font-size:11.5px;color:#8B7F6F;margin-top:6px;">Purchases, loyalty &amp; installments</div>
    </a>
  </div>

  <div style="margin-top:56px;padding-top:20px;border-top:1px solid #E7E0D4;font-size:11.5px;color:#8B7F6F;">
    &copy; {{ date('Y') }} Radharani Jewellery Works
  </div>
</div>
</body>
</html>