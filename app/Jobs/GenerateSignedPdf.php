<?php

namespace App\Jobs;

use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GenerateSignedPdf implements ShouldQueue
{
    use Queueable;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(public readonly Contract $contract) {}

    public function handle(): void
    {
        $contract = $this->contract->fresh()->load(['user', 'signatures', 'landlordSignature', 'tenantSignature']);

        $pdf = Pdf::loadView('pdf.contract', [
            'contract' => $contract,
            'signed'   => true,
        ])->setPaper('letter', 'portrait');

        $output = $pdf->output();
        $path   = "contracts/{$contract->user_id}/contract-{$contract->id}-signed.pdf";

        $stored = Storage::disk('s3')->put($path, $output);

        if (! $stored) {
            Log::error('GenerateSignedPdf: failed to upload to S3', ['contract_id' => $contract->id]);
            throw new \RuntimeException('No se pudo subir el PDF firmado a S3.');
        }

        $contract->update([
            'pdf_path' => $path,
            'pdf_hash' => hash('sha256', $output),
            'status'   => Contract::STATUS_SIGNED,
        ]);

        Log::info('GenerateSignedPdf: signed PDF uploaded to S3', ['contract_id' => $contract->id, 'path' => $path]);
    }
}
