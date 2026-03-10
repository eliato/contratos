<?php

namespace App\Livewire\Admin;

use App\Models\ContractTemplate;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ContractTemplates extends Component
{
    public bool   $showForm   = false;
    public ?int   $editingId  = null;

    public string $name        = '';
    public string $slug        = '';
    public string $description = '';
    public float  $price       = 5.00;
    public bool   $isActive    = true;
    public array  $clauses     = [];
    public string $newClause   = '';

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $tpl = ContractTemplate::findOrFail($id);

        $this->editingId   = $id;
        $this->name        = $tpl->name;
        $this->slug        = $tpl->slug;
        $this->description = $tpl->description ?? '';
        $this->price       = (float) $tpl->price;
        $this->isActive    = $tpl->is_active;
        $this->clauses     = $tpl->default_clauses ?? [];
        $this->showForm    = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function addClause(): void
    {
        $trimmed = trim($this->newClause);
        if ($trimmed === '') {
            return;
        }
        $this->clauses[] = $trimmed;
        $this->newClause = '';
    }

    public function removeClause(int $index): void
    {
        array_splice($this->clauses, $index, 1);
        $this->clauses = array_values($this->clauses);
    }

    public function save(): void
    {
        $uniqueSlug = $this->editingId
            ? Rule::unique('contract_templates', 'slug')->ignore($this->editingId)
            : Rule::unique('contract_templates', 'slug');

        $this->validate([
            'name'  => ['required', 'string', 'max:100'],
            'slug'  => ['required', 'string', 'max:60', 'alpha_dash', $uniqueSlug],
            'price' => ['required', 'numeric', 'min:0', 'max:9999'],
        ]);

        ContractTemplate::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name'            => $this->name,
                'slug'            => $this->slug,
                'description'     => $this->description ?: null,
                'price'           => $this->price,
                'is_active'       => $this->isActive,
                'default_clauses' => $this->clauses ?: null,
            ]
        );

        $this->resetForm();
        $this->showForm = false;
    }

    public function toggleActive(int $id): void
    {
        $tpl = ContractTemplate::findOrFail($id);
        $tpl->update(['is_active' => ! $tpl->is_active]);
    }

    public function delete(int $id): void
    {
        ContractTemplate::findOrFail($id)->delete();
    }

    private function resetForm(): void
    {
        $this->editingId   = null;
        $this->name        = '';
        $this->slug        = '';
        $this->description = '';
        $this->price       = 5.00;
        $this->isActive    = true;
        $this->clauses     = [];
        $this->newClause   = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.contract-templates', [
            'templates' => ContractTemplate::orderBy('name')->get(),
        ])->layout('layouts.admin', ['title' => 'Admin — Plantillas']);
    }
}
