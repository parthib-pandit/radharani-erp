# Aurum ERP Design System

Source: `Aurum ERP — Full Application` Claude-design export (17 screens: Staff Login, Customer Portal
Login, Customer Portal Change Password, Dashboard, Inventory, Purchases, New Sale, Orders, Customers,
Suppliers, Manufacturing, Reports, Finance, Expenses, Accounts, Master Data, Settings).

This is the canonical look for every staff-facing screen. Implemented as Tailwind tokens
(`tailwind.config.js`) + shared Blade components (`resources/views/components/ui/`). Do not hand-roll
inline `style="..."` colors — use the utility classes / tokens below so every page stays in sync.

## Tokens (Tailwind classes)

| Token | Hex | Tailwind class |
|---|---|---|
| Sidebar / darkest surface | `#151515` | `bg-ink` |
| Charcoal (secondary dark) | `#1F1F1F` | `bg-ink-charcoal` |
| Gold (primary accent) | `#B8862D` | `bg-gold` / `text-gold` |
| Gold dark (hover) | `#946B20` | `gold-dark` |
| Gold light | `#D4AF5A` | `gold-light` |
| Gold soft (tint bg) | `#E8D3A2` | `gold-soft` |
| Page background | `#FAF8F3` | `bg-surface-bg` |
| Card / surface | `#FFFFFF` | `bg-surface` |
| Surface muted (table head, chips) | `#F3F1EC` | `bg-surface-muted` |
| Text primary | `#1A1A1A` | `text-ink_text-primary` |
| Text secondary | `#6F6B63` | `text-ink_text-secondary` |
| Text muted | `#9A958B` | `text-ink_text-muted` |
| Border | `#E7E3DA` | `border-line` |
| Border light | `#EFEBE3` | `border-line-light` |
| Success | `#26845B` / bg `#EAF7F0` | `text-success` / `bg-success-bg` |
| Warning | `#B7791F` / bg `#FFF5DC` | `text-warning` / `bg-warning-bg` |
| Danger | `#C94A4A` / bg `#FCECEC` | `text-danger` / `bg-danger-bg` |
| Info | `#3867A6` / bg `#EDF4FC` | `text-info` / `bg-info-bg` |

Font: **Inter** everywhere (staff app). Base radius: `10px` controls, `14px` cards, `999px` pills.
Card shadow: `shadow-card` (`0 2px 10px rgba(30,25,15,.04)`).

## Layout shell

`resources/views/components/layouts/app.blade.php`:
- Fixed 270px dark (`bg-ink`) sidebar: logo mark (34px gold gradient square) + "AURUM ERP" wordmark at
  top, nav links (44px tall, 10px radius, gold gradient background + white text when active, else
  `text-[#C9C4B8]` with muted icon), section labels in `text-ink_text-muted uppercase text-[11px]
  tracking-wide`, user chip pinned at bottom.
- 72px white topbar: search input (left, max-w-400px), notification bell + unread dot, divider, avatar
  (gold gradient circle) + name/role + chevron (right).
- Main content on `bg-surface-bg`.

## Reusable components (`resources/views/components/ui/`)

- `<x-ui.icon name="..." />` — inline SVG, 24x24 viewBox, `stroke="currentColor"` stroke-width 2, round
  caps (lucide-style). Add new icon names as needed; never inline raw `<svg>` in pages.
- `<x-ui.stat-card icon="..." label="..." value="..." delta="..." delta-positive />` — white card,
  14px radius, `shadow-card`, 38px icon chip (`bg-gold-soft/40`-ish tint per stat), value 26px/600,
  delta line in success/danger.
- `<x-ui.badge tone="success|warning|danger|info|neutral">Label</x-ui.badge>` — pill, 24px tall.
- `<x-ui.button variant="primary|secondary|ghost|danger">Label</x-ui.button>` — 40px tall, 8px radius.
- `<x-ui.card>` — white surface, border `line`, 14px radius, `shadow-card`, `p-5`.
- `<x-ui.page-header title="..." subtitle="...">{{ $actions }}</x-ui.page-header>` — page title (24px/700)
  + subtitle (`text-ink_text-secondary`) + right-aligned action slot.
- `<x-ui.table>` — wraps a `<table>`: header row `bg-surface-muted uppercase text-[12px]
  text-ink_text-secondary`, rows 60px tall, `border-b border-line-light`.

## Rollout rules

1. Never invent new colors — pick the closest token above.
2. Replace all inline `style="..."` with Tailwind utility classes / the `ui.*` components.
3. Keep every `wire:model`, `wire:click`, form action, route, and Livewire method call untouched —
   this is a restyle, not a logic change.
4. The customer portal (`components/layouts/guest.blade.php`, `livewire/portal/**`) keeps its own
   lighter, non-sidebar layout (per `CLAUDE.md`: "own minimal layout, not Breeze's staff one") but
   should use the same color tokens/typography for brand consistency.
5. Wireframes in `resources/views/wireframes/` are reference-only static views — do not restyle them.
