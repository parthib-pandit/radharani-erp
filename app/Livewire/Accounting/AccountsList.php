<?php
namespace App\Livewire\Accounting;

use App\Models\Accounting\Account;
use Livewire\Component;

class AccountsList extends Component
{
    public ?int $editingId = null;
    public string $name = '';
    public string $type = 'asset';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:50',
            'type' => 'required|in:asset,liability,income,expense',
        ];
    }

    public function edit(int $id)
    {
        $a = Account::findOrFail($id);
        $this->editingId = $a->id;
        $this->name = $a->name;
        $this->type = $a->type;
    }

    public function save()
    {
        $this->validate();

        Account::updateOrCreate(['id' => $this->editingId], [
            'name' => $this->name,
            'type' => $this->type,
        ]);

        $this->cancel();
        session()->flash('message', 'Account saved.');
    }

    public function cancel()
    {
        $this->reset(['editingId', 'name']);
        $this->type = 'asset';
    }

    public function render()
    {
        return view('livewire.accounting.accounts-list', [
            'accounts' => Account::withCount('transactions')->orderBy('type')->orderBy('name')->get(),
        ])->layout('components.layouts.app', ['title' => 'Accounts — Radharani Jewellery ERP']);
    }
}
