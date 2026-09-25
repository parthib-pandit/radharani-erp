<?php
namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

/**
 * Server-side datatable behaviour shared by the list screens: live search,
 * column sorting, per-page, filter-aware page resets and row selection.
 * Everything round-trips through Livewire, so paging never reloads the page.
 * Pair with <x-ui.datatable> and <x-ui.th>.
 *
 * A component using this implements:
 *   sortableColumns(): array  -> ['uiKey' => 'db_column_or_alias', ...]
 *   defaultSort(): array      -> ['uiKey', 'asc'|'desc']
 *   filterProperties(): array -> property names that should reset paging when changed (optional)
 */
trait WithDataTable
{
    use WithPagination;

    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'sort', except: '')]
    public string $sortField = '';

    #[Url(as: 'dir', except: '')]
    public string $sortDirection = '';

    #[Url(as: 'per', except: 15)]
    public int $perPage = 15;

    /** @var array<int, string> Selected row ids (strings, as checkboxes submit them). */
    public array $selected = [];

    abstract protected function sortableColumns(): array;

    abstract protected function defaultSort(): array;

    protected function filterProperties(): array
    {
        return [];
    }

    public function sortBy(string $field): void
    {
        if (! array_key_exists($field, $this->sortableColumns())) {
            return;
        }

        if ($this->currentSortField() === $field) {
            $this->sortDirection = $this->currentSortDirection() === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function currentSortField(): string
    {
        return array_key_exists($this->sortField, $this->sortableColumns())
            ? $this->sortField
            : $this->defaultSort()[0];
    }

    public function currentSortDirection(): string
    {
        if ($this->sortField === '' || ! array_key_exists($this->sortField, $this->sortableColumns())) {
            return $this->defaultSort()[1];
        }

        return $this->sortDirection === 'asc' ? 'asc' : 'desc';
    }

    protected function applySorting(Builder $query): Builder
    {
        $column = $this->sortableColumns()[$this->currentSortField()];

        return $query->orderBy($column, $this->currentSortDirection());
    }

    protected function perPageValue(): int
    {
        return in_array($this->perPage, [10, 15, 25, 50, 100], true) ? $this->perPage : 15;
    }

    public function updatedWithDataTable($property, $value): void
    {
        $root = explode('.', (string) $property)[0];

        if (in_array($root, array_merge(['search', 'perPage'], $this->filterProperties()), true)) {
            $this->resetPage();
            $this->selected = [];
        }
    }

    public function clearSelection(): void
    {
        $this->selected = [];
    }

    public function resetFilters(): void
    {
        $this->reset(array_merge(['search'], $this->filterProperties()));
        $this->selected = [];
        $this->resetPage();
    }

    public function hasActiveFilters(): bool
    {
        if ($this->search !== '') {
            return true;
        }

        foreach ($this->filterProperties() as $prop) {
            if (! in_array($this->{$prop}, ['', null, false, []], true)) {
                return true;
            }
        }

        return false;
    }
}
