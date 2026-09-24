<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Radharani Jewellery Works — Stock Management</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:#FAF8F4;color:#211D19;}
a{color:inherit;text-decoration:none;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-card{background:#FFFFFF;border:1px solid #E7E0D4;border-radius:10px;transition:border-color .15s ease,box-shadow .15s ease,transform .15s ease;}
.rj-card:hover{border-color:#A9772F;box-shadow:0 6px 18px rgba(28,24,21,0.06);transform:translateY(-1px);}
.wrap{max-width:1080px;margin:0 auto;padding:64px 24px 80px;}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px;margin-top:36px;}
.tag{display:inline-block;font-size:10.5px;letter-spacing:0.08em;text-transform:uppercase;color:#8A5F22;background:#FBF3E6;border-radius:20px;padding:4px 10px;margin-bottom:10px;font-weight:700;}
</style>
</head>
<body>
<div class="wrap">
  <div style="display:flex;align-items:center;gap:14px;">
    <div style="width:52px;height:52px;border-radius:50%;border:1.5px solid #A9772F;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:#1C1815;">
      <span class="rj-serif" style="font-style:italic;font-size:24px;color:#A9772F;">R</span>
    </div>
    <div>
      <div class="rj-serif" style="font-size:30px;color:#211D19;line-height:1.15;">Radharani Jewellery Works</div>
      <div style="font-size:12px;letter-spacing:0.14em;color:#8B7F6F;text-transform:uppercase;margin-top:4px;">Stock Management — Standalone Preview</div>
    </div>
  </div>

  <div style="margin-top:22px;font-size:13.5px;color:#55504A;max-width:640px;line-height:1.6;">
    A fully working, standalone HTML/CSS/JS build of all 12 Stock Management screens. Every screen below opens independently and links to the others — no build step, no server required.
  </div>

  <div class="grid">
    <a class="rj-card" href="{{ route('wireframes.main') }}" style="padding:20px;">
      <span class="tag">Stock</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Overview</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Dashboard, distribution &amp; recent movements</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.inventory') }}" style="padding:20px;">
      <span class="tag">Stock</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Inventory</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Full item list with filters</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.item-detail') }}" style="padding:20px;">
      <span class="tag">Stock</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Item Detail</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Single item profile, QR code &amp; history</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.scan-stock') }}" style="padding:20px;">
      <span class="tag">Stock · Mobile</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Scan Stock</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Camera-style scan simulator</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.move-stock') }}" style="padding:20px;">
      <span class="tag">Stock · Mobile</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Move Stock</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Pick a destination and confirm a move</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.karigar-dispatch') }}" style="padding:20px;">
      <span class="tag">Movements</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Send Stock to Karigar</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Dispatch items for repair or work</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.karigar-return') }}" style="padding:20px;">
      <span class="tag">Movements</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Receive Stock from Karigar</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Log returned items &amp; condition</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.external-movement') }}" style="padding:20px;">
      <span class="tag">Movements</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">External Movements</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Hallmarking, photography &amp; other purposes</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.box-packet') }}" style="padding:20px;">
      <span class="tag">Movements</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Box &amp; Packet Management</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Box → packet → item hierarchy &amp; QR code</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.history') }}" style="padding:20px;">
      <span class="tag">Records</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Item History</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Product, packet &amp; box audit trails</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.logbook') }}" style="padding:20px;">
      <span class="tag">Records</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Logbook</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Daily movement log across the shop</div>
    </a>
    <a class="rj-card" href="{{ route('wireframes.location-report') }}" style="padding:20px;">
      <span class="tag">Records</span>
      <div style="font-size:15.5px;font-weight:700;color:#211D19;">Location Report</div>
      <div style="font-size:12px;color:#8B7F6F;margin-top:5px;">Stock by location, expected &amp; overdue returns</div>
    </a>
  </div>

  <div style="margin-top:56px;padding-top:20px;border-top:1px solid #E7E0D4;display:flex;align-items:center;gap:10px;">
    <div style="width:28px;height:28px;border-radius:50%;background:#3A322A;display:flex;align-items:center;justify-content:center;color:#D9CEBE;font-size:11px;font-weight:600;">EC</div>
    <div style="font-size:12px;color:#8B7F6F;">Echocrew — Owner</div>
  </div>
</div>
</body>
</html>
