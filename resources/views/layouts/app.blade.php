{{-- Livewire's default full-page layout for any component that doesn't call ->layout() itself.
     Every page view already wraps itself in <x-layouts.app> (see resources/views/components/layouts/app.blade.php),
     so this must stay a pure passthrough — wrapping again here would double the <html>/<body>/sidebar
     and leak the old Breeze nav + "Laravel" title through, which is exactly the bug this fixes. --}}
{{ $slot }}
