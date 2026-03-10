<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->is_admin && $user->id === auth()->id()) {
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1) {
                return; // Cannot remove the only admin
            }
        }

        $user->update(['is_admin' => ! $user->is_admin]);
    }

    public function render()
    {
        $users = User::withCount('contracts')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.user-list', [
            'users' => $users,
        ])->layout('layouts.admin', ['title' => 'Admin — Usuarios']);
    }
}
