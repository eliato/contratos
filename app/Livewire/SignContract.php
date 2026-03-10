<?php

namespace App\Livewire;

use App\Jobs\GenerateSignedPdf;
use App\Models\Contract;
use App\Models\Signature;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class SignContract extends Component
{
    public Contract $contract;
    public string   $signerRole    = 'landlord';
    public string   $signatureData = '';
    public bool     $allSigned     = false;
    public bool     $pdfReady      = false;

    public function mount(Contract $contract): void
    {
        abort_if($contract->user_id !== auth()->id(), 403);
        abort_unless($contract->isPaid(), 403, 'El contrato no está pagado.');

        $this->contract = $contract;
        $this->refreshState();
    }

    public function refreshState(): void
    {
        $this->contract->refresh();

        $landlordSigned = $this->contract->landlordSignature()->exists();
        $tenantSigned   = $this->contract->tenantSignature()->exists();

        if ($landlordSigned && $tenantSigned) {
            $this->allSigned = true;
            // pdfReady only when the SIGNED PDF job has completed (status=signed + pdf_path set)
            $this->pdfReady  = $this->contract->status === Contract::STATUS_SIGNED
                               && $this->contract->pdf_path !== null;
        } elseif (! $landlordSigned) {
            $this->signerRole = 'landlord';
        } else {
            $this->signerRole = 'tenant';
        }
    }

    public function saveSignature(): void
    {
        abort_if($this->allSigned, 403);

        $this->validate([
            'signatureData' => ['required', 'string', function ($attr, $val, $fail) {
                if (! str_starts_with($val, 'data:image/png;base64,')) {
                    $fail(__('La firma no es válida.'));
                }
            }],
        ]);

        $imageData = base64_decode(str_replace('data:image/png;base64,', '', $this->signatureData));

        if ($imageData === false || strlen($imageData) < 100) {
            $this->addError('signatureData', __('Por favor dibuja tu firma antes de guardar.'));
            return;
        }

        $directory = "signatures/{$this->contract->user_id}";
        $filename  = "sig-{$this->contract->id}-{$this->signerRole}-" . time() . ".png";
        $path      = "{$directory}/{$filename}";

        Storage::disk('local')->makeDirectory($directory);
        Storage::disk('local')->put($path, $imageData);

        Signature::create([
            'contract_id'    => $this->contract->id,
            'signer_role'    => $this->signerRole,
            'signature_path' => $path,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent() ?? '',
            'signed_at'      => now(),
        ]);

        $this->signatureData = '';
        $this->dispatch('clear-canvas');

        $this->refreshState();

        if ($this->allSigned) {
            set_time_limit(120); // dompdf + S3 upload can take several seconds
            GenerateSignedPdf::dispatch($this->contract);
            $this->refreshState(); // With sync queue: job already ran, pick up status=signed
        }
    }

    public function clearCanvas(): void
    {
        $this->signatureData = '';
        $this->dispatch('clear-canvas');
    }

    public function render()
    {
        return view('livewire.sign-contract', [
            'landlordSignature' => $this->contract->landlordSignature,
            'tenantSignature'   => $this->contract->tenantSignature,
        ])->layout('layouts.app', ['title' => 'Firmar Contrato #' . $this->contract->id]);
    }
}
