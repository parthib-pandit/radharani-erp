@props(['paginator' => null, 'perPageOptions' => [10, 15, 25, 50, 100]])
{{--
    Server-side datatable shell for components using App\Livewire\Concerns\WithDataTable.
    Slots: toolbar (search + filters), head (<th> cells), default (rows), bulk (actions shown while rows are selected).
    Sorting, filtering and paging all round-trip through Livewire. The page never reloads.
--}}
<section data-dt {{ $attributes->merge(['class' => 'relative bg-white border border-line-light rounded-card shadow-card scroll-mt-24']) }}>
    @isset($toolbar)
        <div class="flex flex-wrap items-center gap-2.5 px-4 py-3.5 border-b border-line-light">
            {{ $toolbar }}
        </div>
    @endisset

    @isset($bulk)
        {{ $bulk }}
    @endisset

    {{-- Thin progress bar while any request is in flight --}}
    <div wire:loading.delay class="absolute left-0 right-0 top-0 h-[2px] overflow-hidden rounded-t-card z-10">
        <div class="h-full w-2/5 gold-sheen animate-progress"></div>
    </div>

    <div class="relative overflow-x-auto" wire:loading.delay.class="opacity-60 pointer-events-none">
        <table class="rj-table">
            @isset($head)
                <thead><tr>{{ $head }}</tr></thead>
            @endisset
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if ($paginator)
        <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-line-light bg-surface-sunken/60 rounded-b-card">
            <label class="flex items-center gap-2 text-[12.5px] text-ink_text-secondary">
                Rows
                <select wire:model.live="perPage" class="rj-select rj-input-sm w-[76px] pl-2.5 pr-7 bg-[right_0.4rem_center]">
                    @foreach ($perPageOptions as $n)
                        <option value="{{ $n }}">{{ $n }}</option>
                    @endforeach
                </select>
            </label>
            <div class="flex-1 min-w-0">
                @if ($paginator->hasPages())
                    {{ $paginator->links() }}
                @else
                    <p class="text-[12.5px] text-ink_text-secondary text-right">
                        {{ $paginator->total() }} {{ \Illuminate\Support\Str::plural('record', $paginator->total()) }}
                    </p>
                @endif
            </div>
        </div>
    @endif
</section>
