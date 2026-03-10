<?php

namespace App\Livewire\Admin;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentList extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $statusFilter = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $payments = Payment::with(['contract.user'])
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('wompi_transaction_id', 'like', "%{$this->search}%")
                      ->orWhereHas('contract.user', fn ($q) =>
                          $q->where('name', 'like', "%{$this->search}%")
                            ->orWhere('email', 'like', "%{$this->search}%")
                      );
                });
            })
            ->latest()
            ->paginate(25);

        return view('livewire.admin.payment-list', [
            'payments' => $payments,
        ])->layout('layouts.admin', ['title' => 'Admin — Pagos']);
    }
}
