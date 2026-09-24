<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'Radharani Jewellery ERP' }}</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
</head>
<body class="font-sans antialiased">
<div class="flex min-h-screen">

  {{-- Sidebar --}}
  <aside class="w-[270px] shrink-0 bg-ink flex flex-col h-screen sticky top-0 overflow-hidden">
    <div class="h-[72px] shrink-0 flex items-center gap-2.5 px-6 border-b border-white/[.06]">
      <div class="w-[34px] h-[34px] shrink-0 rounded-[9px] bg-gradient-to-br from-gold-light to-gold flex items-center justify-center">
        <x-ui.icon name="gem" :size="17" class="text-ink" />
      </div>
      <div class="flex flex-col gap-px min-w-0">
        <div class="text-sm font-bold text-white tracking-wide truncate">RADHARANI ERP</div>
        <div class="text-[9.5px] font-semibold tracking-widest text-[#8E8A80] truncate">JEWELLERY WORKS</div>
      </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 flex flex-col gap-0.5">
      <x-ui.nav-link route="dashboard" icon="grid" label="Dashboard" />

      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Stock</div>
      <x-ui.nav-link route="stock.boxes" icon="archive" label="Box & Packet" />
      <x-ui.nav-link route="stock.packets" icon="package" label="Packets" />
      <x-ui.nav-link route="stock.items" icon="gem" label="Inventory" />
      <x-ui.nav-link route="stock.configurator" icon="settings" label="Configurator" />

      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Movements</div>
      <x-ui.nav-link route="movements.vault-counter" icon="repeat" label="Vault ↔ Counter" />
      <x-ui.nav-link route="movements.karigar-dispatch" icon="truck" label="Karigar Dispatch" />
      <x-ui.nav-link route="movements.karigar-return" icon="truck" label="Karigar Return" />
      <x-ui.nav-link route="movements.hallmark-dispatch" icon="tag" label="Hallmarking Dispatch" />
      <x-ui.nav-link route="movements.hallmark-return" icon="tag" label="Hallmarking Return" />
      <x-ui.nav-link route="movements.custom-purpose" icon="camera" label="Photo / Custom" />
      <x-ui.nav-link route="movements.pending-review" icon="clock" label="Pending Review" />

      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Exchange &amp; Refinery</div>
      <x-ui.nav-link route="exchange.new" icon="plus" label="New Exchange Entry" />
      <x-ui.nav-link route="exchange.tracker" icon="clock" label="Status Tracker" />
      <x-ui.nav-link route="exchange.valuation" icon="scale" label="Final Valuation" />
      <x-ui.nav-link route="exchange.refinery.send" icon="upload" label="Refinery — Send" />
      <x-ui.nav-link route="exchange.refinery.return" icon="download" label="Refinery — Return" />

      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Custom Orders</div>
      <x-ui.nav-link route="orders.new" icon="plus" label="New Order" />
      <x-ui.nav-link route="orders.board" icon="clipboard" label="Status Board" />
      <x-ui.nav-link route="orders.reminders" icon="bell" label="Ready Reminders" />

      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Pricing &amp; Rates</div>
      <x-ui.nav-link route="pricing.rates" icon="bar-chart" label="Daily Rate Entry" />
      <x-ui.nav-link route="pricing.rates.history" icon="clock" label="Rate History" />
      <x-ui.nav-link route="pricing.making-charges" icon="hash" label="Making Charges" />
      <x-ui.nav-link route="pricing.discounts" icon="percent" label="Discount Rules" />
      <x-ui.nav-link route="pricing.additional-charges" icon="plus" label="Additional Charges" />

      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Sales &amp; Billing</div>
      <x-ui.nav-link route="sales.new" icon="receipt" label="New Sale" />
      <x-ui.nav-link route="sales.verification" icon="check" label="Verification Queue" />
      <x-ui.nav-link route="sales.history" icon="clock" label="Sales History" />

      @can('purchase.manage')
      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Purchases &amp; Vendors</div>
      <x-ui.nav-link route="purchases.vendors" icon="truck" label="Vendors" />
      <x-ui.nav-link route="purchases.new" icon="plus" label="New Purchase" />
      <x-ui.nav-link route="purchases.list" icon="cart" label="Purchase List" />
      @endcan

      @can('ledger.view')
      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Accounting</div>
      <x-ui.nav-link route="accounting.ledger" icon="book" label="Ledger" />
      <x-ui.nav-link route="accounting.accounts" icon="wallet" label="Accounts" />
      <x-ui.nav-link route="accounting.tally-export" icon="download" label="Tally Export" />
      @endcan

      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Notifications</div>
      <x-ui.nav-link route="notifications.queue" icon="bell" label="Pending Messages" />

      @can('loyalty.manage')
      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Loyalty</div>
      <x-ui.nav-link route="loyalty.award" icon="gift" label="Award Points" />
      <x-ui.nav-link route="loyalty.ledger" icon="star" label="Points Ledger" />
      @endcan

      @can('customer.manage')
      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Installment Scheme</div>
      <x-ui.nav-link route="installments.enrol" icon="plus" label="Scheme Enrolment" />
      <x-ui.nav-link route="installments.monthly-status" icon="calendar" label="Monthly Payment Status" />
      <x-ui.nav-link route="installments.list" icon="file-text" label="Scheme List" />
      @endcan

      @can('audit.view')
      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Reports</div>
      <x-ui.nav-link route="reports.dashboard" icon="bar-chart" label="Owner Dashboard" />
      <x-ui.nav-link route="reports.logbook" icon="book" label="Daily Logbook" />
      <x-ui.nav-link route="reports.staff-activity" icon="users" label="Staff Activity" />
      <x-ui.nav-link route="reports.location" icon="map-pin" label="Location Report" />
      @endcan

      @canany(['employee.manage','user.manage','role.manage','loyalty.manage','customer.manage','audit.view'])
      <div class="text-[11px] font-semibold tracking-widest text-[#8E8A80] uppercase px-3.5 mt-4 mb-1.5">Admin</div>
      @can('employee.manage')
      <x-ui.nav-link route="admin.employees" icon="users" label="Employees" />
      @endcan
      @can('user.manage')
      <x-ui.nav-link route="admin.users" icon="user" label="Users" />
      @endcan
      @can('role.manage')
      <x-ui.nav-link route="admin.roles" icon="lock" label="Roles & Permissions" />
      @endcan
      @can('loyalty.manage')
      <x-ui.nav-link route="admin.loyalty-settings" icon="settings" label="Loyalty Settings" />
      <x-ui.nav-link route="admin.referrals" icon="gift" label="Referrals" />
      @endcan
      @can('customer.manage')
      <x-ui.nav-link route="admin.customers" icon="users" label="Customers" />
      @endcan
      @can('audit.view')
      <x-ui.nav-link route="admin.audit-log" icon="clipboard" label="Audit Log" />
      @endcan
      @endcanany
    </nav>

    <div class="shrink-0 border-t border-white/[.06] p-3">
      <div class="flex items-center gap-2.5 px-2.5 py-2.5 rounded-control hover:bg-white/5">
        <div class="w-8 h-8 shrink-0 rounded-full bg-[#2A2926] text-gold-light flex items-center justify-center text-xs font-semibold">
            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
          <div class="text-[13px] font-medium text-white truncate">{{ auth()->user()->name ?? 'Staff' }}</div>
          <div class="text-[11px] text-[#8E8A80] truncate">{{ auth()->user()?->getRoleNames()->first() ?? 'Staff' }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" aria-label="Log out" class="text-[#8E8A80] hover:text-white p-1">
            <x-ui.icon name="log-out" :size="15" />
          </button>
        </form>
      </div>
    </div>
  </aside>

  {{-- Main column --}}
  <div class="flex-1 min-w-0 flex flex-col bg-surface-bg">
    {{-- Topbar --}}
    <header class="h-[72px] shrink-0 bg-white border-b border-line flex items-center justify-between px-[30px] gap-6">
      @livewire('layout.global-search')
      <div class="flex items-center gap-[18px] shrink-0">
        <a href="{{ route('notifications.queue') }}" aria-label="Notifications" class="relative text-ink_text-secondary p-1.5">
          <x-ui.icon name="bell" :size="19" />
          @php($unreadCount = \App\Models\Notification\PendingNotification::where('status', 'pending')->count())
          @if ($unreadCount > 0)
            <span class="absolute top-0 right-0 min-w-[15px] h-[15px] px-[3px] rounded-full bg-danger border-[1.5px] border-white text-white text-[9px] font-bold flex items-center justify-center">
              {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
          @endif
        </a>
        <div class="w-px h-6 bg-line"></div>
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
          <button type="button" @click="open = !open" class="flex items-center gap-2.5">
            <div class="w-[34px] h-[34px] rounded-full bg-gradient-to-br from-gold-soft to-gold"></div>
            <div class="flex flex-col text-left">
              <div class="text-[13px] font-semibold text-ink_text-primary">{{ auth()->user()->name ?? 'Staff' }}</div>
              <div class="text-[11px] text-ink_text-muted">{{ auth()->user()?->getRoleNames()->first() ?? 'Staff' }}</div>
            </div>
            <x-ui.icon name="chevron-down" :size="14" class="text-ink_text-muted" />
          </button>
          <div x-show="open" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-line rounded-card shadow-card py-1.5 z-50">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-3.5 py-2 text-sm text-ink_text-primary hover:bg-surface-muted">
              <x-ui.icon name="user" :size="15" class="text-ink_text-secondary" /> Profile
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="w-full flex items-center gap-2.5 px-3.5 py-2 text-sm text-ink_text-primary hover:bg-surface-muted">
                <x-ui.icon name="log-out" :size="15" class="text-ink_text-secondary" /> Log out
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>

    @if (session('status'))
      <div class="mx-8 mt-6 bg-success-bg text-success rounded-control px-3.5 py-2.5 text-sm">{{ session('status') }}</div>
    @endif

    <main class="flex-1 p-8">
      {{ $slot }}
    </main>
  </div>
</div>
@livewireScripts
</body>
</html>
