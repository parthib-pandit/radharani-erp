<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Staff Sign In — Radharani Jewellery ERP</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>[x-cloak]{display:none !important;}</style>
@livewireStyles
</head>
<body class="font-sans antialiased text-ink_text-primary">
<div class="min-h-screen flex">

  {{-- Brand panel --}}
  <div class="w-[44%] min-w-[380px] bg-ink text-white/90 p-14 flex flex-col justify-between">
    <div class="flex items-center gap-2.5">
      <div class="w-[34px] h-[34px] shrink-0 rounded-[9px] bg-gradient-to-br from-gold-light to-gold flex items-center justify-center">
        <x-ui.icon name="gem" :size="17" class="text-ink" />
      </div>
      <div>
        <div class="text-sm font-bold text-white tracking-wide">RADHARANI ERP</div>
        <div class="text-[9.5px] font-semibold tracking-widest text-[#8E8A80]">JEWELLERY WORKS</div>
      </div>
    </div>

    <div>
      <div class="text-3xl font-semibold leading-tight text-white max-w-[360px]">Precision. Trust. Craftsmanship.</div>
      <div class="text-[13.5px] text-[#C9C4B8] leading-relaxed mt-3.5 max-w-[340px]">
        Internal staff access for vault, counter, karigar and sales tracking. Every entry here is tied to your account.
      </div>

      <div class="flex flex-col gap-3 mt-7">
        @foreach ([
          'Access is scoped to your role',
          'Separate from the customer portal',
          'Every action is logged for audit',
        ] as $line)
        <div class="flex items-center gap-2.5">
          <span class="w-[22px] h-[22px] shrink-0 rounded-full bg-white/10 flex items-center justify-center">
            <x-ui.icon name="check" :size="11" class="text-gold-light" />
          </span>
          <span class="text-[12.5px] text-[#C9C4B8]">{{ $line }}</span>
        </div>
        @endforeach
      </div>
    </div>

    <div class="text-[11px] text-[#8E8A80]">© {{ date('Y') }} Radharani Jewellery Works · Staff access only · Powered by Echocrew</div>
  </div>

  {{-- Form panel --}}
  <div class="flex-1 bg-surface-bg flex items-center justify-center p-10">
    <div class="w-full max-w-[360px]" x-data="{ method: '{{ old('method', 'email') }}', showPassword: false }">

      <div class="text-[11px] font-semibold tracking-widest text-gold uppercase mb-2">Staff Sign In</div>
      <div class="text-xl font-semibold mb-1">Welcome back</div>
      <div class="text-[13px] text-ink_text-secondary mb-6">Sign in with your work email or phone.</div>

      @session('status')
        <div class="bg-success-bg text-success rounded-control px-3.5 py-2.5 mb-4 text-sm">{{ $value }}</div>
      @endsession

      {{-- Identifier method toggle --}}
      <div class="flex gap-1 bg-surface-muted rounded-control p-1 mb-5">
        <button type="button" @click="method = 'email'"
          :class="method === 'email' ? 'bg-white text-ink_text-primary shadow-sm' : 'bg-transparent text-ink_text-secondary'"
          class="flex-1 h-[34px] rounded-[6px] text-[12.5px] font-semibold">Email</button>
        <button type="button" @click="method = 'phone'"
          :class="method === 'phone' ? 'bg-white text-ink_text-primary shadow-sm' : 'bg-transparent text-ink_text-secondary'"
          class="flex-1 h-[34px] rounded-[6px] text-[12.5px] font-semibold">Phone</button>
      </div>

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="method" x-bind:value="method">

        {{-- Email identifier (live) --}}
        <div x-show="method === 'email'" x-cloak>
          <label for="email" class="block text-[11.5px] text-ink_text-secondary mb-1">Email address</label>
          <input id="email" type="email" x-bind:name="method === 'email' ? 'login' : null" value="{{ old('method') === 'email' ? old('login') : '' }}"
            x-bind:required="method === 'email'" autofocus autocomplete="username"
            placeholder="you@radharanierp.com" class="rj-input">
        </div>

        {{-- Phone identifier --}}
        <div x-show="method === 'phone'" x-cloak>
          <label for="phone" class="block text-[11.5px] text-ink_text-secondary mb-1">Phone number</label>
          <input id="phone" type="tel" x-bind:name="method === 'phone' ? 'login' : null" value="{{ old('method') === 'phone' ? old('login') : '' }}"
            x-bind:required="method === 'phone'" autocomplete="username"
            placeholder="98325 03125" class="rj-input">
        </div>

        @error('login')
          <div class="text-danger text-[11.5px] mt-1.5">{{ $message }}</div>
        @enderror

        <div class="mt-4">
          <label for="password" class="block text-[11.5px] text-ink_text-secondary mb-1">Password</label>
          <div class="relative">
            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="current-password"
              placeholder="Enter your password" class="rj-input pr-12">
            <button type="button" @click="showPassword = !showPassword" aria-label="Toggle password visibility"
              class="absolute right-2 top-0 h-10 bg-transparent border-0 text-ink_text-secondary text-[11.5px] font-semibold">
              <span x-text="showPassword ? 'Hide' : 'Show'"></span>
            </button>
          </div>
          @error('password')
            <div class="text-danger text-[11.5px] mt-1.5">{{ $message }}</div>
          @enderror
        </div>

        <div class="flex items-center justify-between mt-4">
          <label class="flex items-center gap-2 text-[12.5px] text-ink_text-secondary cursor-pointer">
            <input type="checkbox" name="remember" class="accent-gold">
            Remember me
          </label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-[12.5px] font-semibold text-gold">Forgot password?</a>
          @endif
        </div>

        <button type="submit" class="w-full h-[42px] rounded-control border-0 font-bold text-[13.5px] mt-5 bg-gold text-white hover:bg-gold-dark cursor-pointer transition-colors">
          Sign In
        </button>
      </form>

      <div class="text-center text-[11.5px] text-ink_text-secondary mt-5">Trouble signing in? Ask the shop owner or manager.</div>
    </div>
  </div>
</div>
@livewireScripts
</body>
</html>
