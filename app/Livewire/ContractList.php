<?php

namespace App\Livewire;

use App\Models\Contract;
use Livewire\Component;

class ContractList extends Component
{
    public bool   $showDeleteModal = false;
    public ?int   $deletingId      = null;
    public string $deletingName    = '';

    public function mount(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->deletingName    = '';
    }

    public function confirmDelete(int $id): void
    {
        $contract = Contract::findOrFail($id);
        abort_if($contract->user_id !== auth()->id(), 403);

        $this->deletingId      = $id;
        $this->deletingName    = $contract->tenant_name;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->deletingId      = null;
        $this->deletingName    = '';
    }

    public function delete(): void
    {
        if (! $this->deletingId) {
            return;
        }

        $contract = Contract::findOrFail($this->deletingId);
        abort_if($contract->user_id !== auth()->id(), 403);

        $contract->delete();
        $this->cancelDelete();
    }

    public function render()
    {
        return view('livewire.contract-list', [
            'contracts' => auth()->user()->contracts()->latest()->get(),
        ]);
    }
}
