<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Settings extends Component
{
    #[Validate('required|integer|min:5|max:43200')]
    public int $sessionLifetime = 120;

    public bool $saved = false;

    public function mount(): void
    {
        $this->sessionLifetime = (int) Setting::cached('session_lifetime', config('session.lifetime', 120));
    }

    public function save(): void
    {
        $this->validate();

        Setting::set('session_lifetime', $this->sessionLifetime);

        // Update running config immediately
        config(['session.lifetime' => $this->sessionLifetime]);

        $this->saved = true;
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.admin', ['title' => __('Configuración')]);
    }
}
