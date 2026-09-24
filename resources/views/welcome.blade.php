<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Radharani Jewellery ERP</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-surface-bg text-ink_text-primary">
<div class="max-w-[720px] mx-auto px-6 pt-24 pb-20 text-center">
  <div class="w-16 h-16 rounded-full bg-ink flex items-center justify-center mx-auto mb-5">
    <x-ui.icon name="gem" :size="28" class="text-gold" />
  </div>

  <div class="text-[34px] font-bold">Radharani Jewellery ERP</div>
  <div class="text-[13px] tracking-[.14em] text-ink_text-muted uppercase mt-2">Stock Management &amp; Customer Portal</div>

  <div class="text-sm text-ink_text-secondary max-w-[460px] mx-auto mt-6 mb-10 leading-relaxed">
    Every piece tracked from vault to counter. Current gold and silver rates,
    reflected instantly across the collection.
  </div>

  <div class="grid grid-cols-2 gap-4 max-w-[480px] mx-auto">
    <a href="{{ route('login') }}" class="bg-white border border-line rounded-card p-6 hover:border-gold hover:shadow-card hover:-translate-y-0.5 transition-all">
      <div class="text-[15px] font-bold">Staff Login</div>
      <div class="text-[11.5px] text-ink_text-secondary mt-1.5">Inventory, movements &amp; sales</div>
    </a>
    <a href="{{ route('portal.login') }}" class="bg-white border border-line rounded-card p-6 hover:border-gold hover:shadow-card hover:-translate-y-0.5 transition-all">
      <div class="text-[15px] font-bold">Customer Portal</div>
      <div class="text-[11.5px] text-ink_text-secondary mt-1.5">Purchases, loyalty &amp; installments</div>
    </a>
  </div>

  <div class="mt-14 pt-5 border-t border-line text-[11.5px] text-ink_text-secondary">
    &copy; {{ date('Y') }} Radharani Jewellery Works
    <div class="mt-1 text-[10.5px] text-ink_text-muted">Powered by Echocrew</div>
  </div>
</div>
</body>
</html>
