<?php

namespace App\Livewire\Admin;

use App\Models\Contract;
use App\Models\Payment;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $contractsByStatus = Contract::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('livewire.admin.dashboard', [
            'totalUsers'        => User::count(),
            'newUsersThisMonth' => User::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
            'totalContracts'    => Contract::count(),
            'contractsByStatus' => $contractsByStatus,
            'totalRevenue'      => Payment::where('status', 'completed')->sum('amount'),
            'revenueThisMonth'  => Payment::where('status', 'completed')
                                           ->whereMonth('paid_at', now()->month)
                                           ->whereYear('paid_at', now()->year)
                                           ->sum('amount'),
            'recentContracts'   => Contract::with('user')->latest()->take(8)->get(),
        ])->layout('layouts.admin', ['title' => 'Admin — Dashboard']);
    }
}
