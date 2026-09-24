<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

/**
 * Audit Log Viewer — reads spatie/laravel-activitylog's activity_log
 * table (the package was already installed per CLAUDE.md's build-status
 * table, just not wired to any model yet). Movement, Sale, and Purchase
 * now use the LogsActivity trait (added alongside this screen) so every
 * new movement/sale/purchase writes a real audit row here — exactly the
 * tamper-evident trail rule 1 (never update/delete those tables) is meant
 * to support. Entries created before this change won't appear, since
 * nothing was logging until now.
 */
class AuditLogViewer extends Component
{
    use WithPagination;

    public string $logNameFilter = 'all';
    public string $search = '';

    public function updatingLogNameFilter() { $this->resetPage(); }
    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.audit-log-viewer', [
            'activities' => Activity::with('causer')
                ->when($this->logNameFilter !== 'all', fn ($q) => $q->where('log_name', $this->logNameFilter))
                ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
                ->orderByDesc('id')
                ->paginate(30),
        ])->layout('components.layouts.app', ['title' => 'Audit Log — Radharani Jewellery']);
    }
}
