<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Services\WompiService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateContract extends Component
{
    public int  $step       = 1;
    public int  $totalSteps = 5;
    public bool $submitted  = false;
    public int  $contractId = 0; // persists across retries to avoid duplicate contracts

    // Step 1 — Arrendador
    #[Validate('required|string|max:255')]
    public string $landlord_name = '';

    #[Validate('required|string|max:20')]
    public string $landlord_dui = '';

    #[Validate('required|string|max:500')]
    public string $landlord_address = '';

    // Step 2 — Arrendatario
    #[Validate('required|string|max:255')]
    public string $tenant_name = '';

    #[Validate('required|string|max:20')]
    public string $tenant_dui = '';

    #[Validate('required|string|max:500')]
    public string $tenant_address = '';

    // Step 3 — Inmueble
    #[Validate('required|string|max:500')]
    public string $property_address = '';

    #[Validate('required|numeric|min:1')]
    public string $rent_amount = '';

    #[Validate('required|numeric|min:0')]
    public string $deposit_amount = '';

    #[Validate('required|integer|min:1|max:120')]
    public int $duration_months = 12;

    #[Validate('required|date')]
    public string $start_date = '';

    // Step 4 — Cláusulas (solo las adicionales del usuario; las de plantilla se fusionan al guardar)
    public array  $custom_clauses        = [];
    public string $new_clause            = '';
    public int    $templateClausesCount  = 0;

    // ─── Lifecycle ──────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $template = ContractTemplate::where('slug', 'arrendamiento_vivienda')
            ->where('is_active', true)
            ->first();

        $this->templateClausesCount = count($template?->default_clauses ?? []);
    }

    // ─── Step helpers ───────────────────────────────────────────────────────────

    private function stepFields(): array
    {
        return [
            1 => ['landlord_name', 'landlord_dui', 'landlord_address'],
            2 => ['tenant_name', 'tenant_dui', 'tenant_address'],
            3 => ['property_address', 'rent_amount', 'deposit_amount', 'duration_months', 'start_date'],
            4 => [],
        ];
    }

    public function nextStep(): void
    {
        $fields = $this->stepFields()[$this->step] ?? [];

        if (! empty($fields)) {
            $rules = array_intersect_key($this->getRules(), array_flip($fields));
            $this->validate($rules);
        }

        if ($this->step < $this->totalSteps) {
            $this->step++;
        }
    }

    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->step) {
            $this->step = $step;
        }
    }

    // ─── Clauses ────────────────────────────────────────────────────────────────

    public function addClause(): void
    {
        $clause = trim($this->new_clause);
        if ($clause !== '' && mb_strlen($clause) <= 1000) {
            $this->custom_clauses[] = $clause;
            $this->new_clause = '';
        }
    }

    public function removeClause(int $index): void
    {
        unset($this->custom_clauses[$index]);
        $this->custom_clauses = array_values($this->custom_clauses);
    }

    // ─── Submit ─────────────────────────────────────────────────────────────────

    public function submit(): void
    {
        if ($this->submitted) {
            return;
        }

        $this->validate();

        // Allow up to 2 minutes — WOMPI makes 2 external HTTP calls
        set_time_limit(120);

        $this->submitted = true;

        // Merge template base clauses + user's extra clauses into the final list
        $template   = ContractTemplate::where('slug', 'arrendamiento_vivienda')
            ->where('is_active', true)
            ->first();
        $baseClauses  = $template?->default_clauses ?? [];
        $extraClauses = array_values(array_filter($this->custom_clauses, fn ($c) => trim($c) !== ''));
        $allClauses   = array_merge($baseClauses, $extraClauses);

        // Reuse existing contract if a previous WOMPI call failed
        if ($this->contractId) {
            $contract = Contract::find($this->contractId);
        } else {
            $contract = auth()->user()->contracts()->create([
                'type'             => 'arrendamiento_vivienda',
                'landlord_name'    => $this->landlord_name,
                'landlord_dui'     => $this->landlord_dui,
                'landlord_address' => $this->landlord_address,
                'tenant_name'      => $this->tenant_name,
                'tenant_dui'       => $this->tenant_dui,
                'tenant_address'   => $this->tenant_address,
                'property_address' => $this->property_address,
                'rent_amount'      => $this->rent_amount,
                'deposit_amount'   => $this->deposit_amount,
                'duration_months'  => $this->duration_months,
                'start_date'       => $this->start_date,
                'custom_clauses'   => $allClauses,
                'status'           => Contract::STATUS_PENDING_PAYMENT,
            ]);
            $this->contractId = $contract->id;
        }

        try {
            $wompi  = app(WompiService::class);
            $result = $wompi->createTransaction($contract);

            if (empty($result['redirect_url'])) {
                throw new \RuntimeException('No se recibió URL de pago de WOMPI.');
            }

            $this->redirect($result['redirect_url'], navigate: false);
        } catch (\Throwable $e) {
            $this->submitted = false;
            $this->addError('payment', 'No se pudo conectar con WOMPI. Intenta de nuevo. (' . $e->getMessage() . ')');
        }
    }

    public function render()
    {
        return view('livewire.create-contract')
            ->layout('layouts.app', ['title' => 'Nuevo Contrato']);
    }
}
