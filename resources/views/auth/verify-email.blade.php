<x-layouts.guest title="Verify your email · Radharani Jewellery Works">
<x-auth.shell audience="staff" heading="Check your inbox." lede="We've sent a link to confirm your email address. Open it on any device and you're in.">
    <p class="text-[12px] font-bold uppercase tracking-[0.18em] text-gold-dark">One last step</p>
    <h1 class="font-display text-[40px] leading-[1.08] font-semibold mt-2">Verify your email</h1>
    <p class="text-[14px] text-ink_text-secondary mt-2">
        Click the link we sent to <span class="font-semibold text-ink_text-primary">{{ auth()->user()->email }}</span>. It can take a minute to arrive, so check spam too.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="flex items-start gap-2.5 mt-6 px-4 py-3 rounded-xl bg-success-bg text-success text-[13px] font-medium">
            <x-ui.icon name="check-circle" :size="16" class="shrink-0 mt-px" /> A fresh link is on its way.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mt-8" x-data="{ submitting: false }" x-on:submit="submitting = true">
        @csrf
        <x-auth.submit busy="Sending">Send the link again</x-auth.submit>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-[13px] font-semibold text-ink_text-secondary hover:text-gold-dark">Sign out and use another account</button>
    </form>
</x-auth.shell>
</x-layouts.guest>
