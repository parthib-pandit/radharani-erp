<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $title ?? 'Radharani Jewellery Works' }}</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Public+Sans:wght@400;500;600;700&display=swap');
:root{
  --bg:#FAF8F4; --card:#FFFFFF; --border:#E7E0D4; --text:#211D19;
  --muted:#8B7F6F; --accent:#A9772F; --accent-light:#FBF3E6; --accent-dark:#8A5F22;
}
*{box-sizing:border-box;}
body{margin:0;font-family:'Public Sans',-apple-system,BlinkMacSystemFont,sans-serif;background:var(--bg);color:var(--text);}
a{color:inherit;text-decoration:none;}
.rj-serif{font-family:'Newsreader',Georgia,serif;}
.rj-app{display:flex;min-height:100vh;}
.rj-sidebar{width:220px;min-width:220px;background:#fff;border-right:1px solid var(--border);padding:20px 12px;}
.rj-sidebar h1{font-family:'Newsreader',Georgia,serif;font-size:17px;margin:0 0 2px 8px;}
.rj-sidebar p{font-size:10.5px;color:var(--muted);letter-spacing:.06em;text-transform:uppercase;margin:0 0 20px 8px;}
.rj-nav-link{display:block;padding:10px 12px;border-radius:8px;font-size:13px;color:var(--text);margin-bottom:2px;}
.rj-nav-link:hover{background:#F3EFE8;}
.rj-nav-link.active{background:var(--accent-light);color:var(--accent-dark);font-weight:700;}
.rj-main{flex-grow:1;padding:32px 36px;max-width:1200px;}
.rj-card{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:20px;}
.rj-input, .rj-select{border:1px solid var(--border);border-radius:8px;padding:8px 12px;font-size:13px;font-family:inherit;background:#fff;color:var(--text);}
.rj-input:focus, .rj-select:focus{outline:none;border-color:var(--accent);}
.rj-btn-primary{background:var(--accent);color:#FFF7EC;font-size:13.5px;font-weight:700;padding:9px 20px;border:none;border-radius:8px;cursor:pointer;}
.rj-btn-secondary{background:#fff;border:1px solid var(--border);color:var(--text);font-size:13.5px;font-weight:600;padding:9px 20px;border-radius:8px;cursor:pointer;}
.rj-tag{display:inline-block;font-size:10.5px;font-weight:700;letter-spacing:.04em;padding:3px 10px;border-radius:20px;}
.rj-tag-stock{background:#E4EDE3;color:#3F6B4A;}
.rj-tag-dispatched{background:#FBF0DC;color:#8A5F22;}
.rj-tag-sold{background:#EFEAE1;color:#75695B;}
table.rj-table{width:100%;border-collapse:collapse;font-size:13px;}
table.rj-table th{text-align:left;padding:10px 8px;border-bottom:1px solid var(--border);color:var(--muted);font-weight:600;font-size:11.5px;text-transform:uppercase;letter-spacing:.03em;}
table.rj-table td{padding:12px 8px;border-bottom:1px solid #F3EFE8;}
.rj-flash{background:#E4EDE3;color:#3F6B4A;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;}
</style>
</head>
<body>
<div class="rj-app">
  <div class="rj-sidebar">
    <h1>Radharani</h1>
    <p>Jewellery Works</p>
    <a class="rj-nav-link {{ request()->routeIs('stock.boxes') ? 'active' : '' }}" href="{{ route('stock.boxes') }}">Box &amp; Packet</a>
    <a class="rj-nav-link {{ request()->routeIs('stock.packets') ? 'active' : '' }}" href="{{ route('stock.packets') }}">Packets</a>
    <a class="rj-nav-link {{ request()->routeIs('stock.items') ? 'active' : '' }}" href="{{ route('stock.items') }}">Inventory</a>
    @can('employee.manage')
    <div style="font-size:10px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin:18px 0 6px 12px;">Admin</div>
    <a class="rj-nav-link {{ request()->routeIs('admin.employees') ? 'active' : '' }}" href="{{ route('admin.employees') }}">Employees</a>
    @endcan
    @can('user.manage')
    <a class="rj-nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users</a>
    @endcan
    @can('role.manage')
    <a class="rj-nav-link {{ request()->routeIs('admin.roles') ? 'active' : '' }}" href="{{ route('admin.roles') }}">Roles &amp; Permissions</a>
    @endcan
    @can('loyalty.manage')
    <a class="rj-nav-link {{ request()->routeIs('admin.loyalty-settings') ? 'active' : '' }}" href="{{ route('admin.loyalty-settings') }}">Loyalty Settings</a>
    <a class="rj-nav-link {{ request()->routeIs('admin.referrals') ? 'active' : '' }}" href="{{ route('admin.referrals') }}">Referrals</a>
    @endcan
    @can('customer.manage')
    <a class="rj-nav-link {{ request()->routeIs('admin.customers') ? 'active' : '' }}" href="{{ route('admin.customers') }}">Customers</a>
    @endcan
  </div>
  <div class="rj-main">
    {{ $slot }}
  </div>
</div>
</body>
</html>
