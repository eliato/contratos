<?php

namespace App\Services;

use App\Jobs\GenerateContractPdf;
use App\Models\Contract;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WompiService
{
    private string $apiUrl  = 'https://api.wompi.sv';
    private string $authUrl = 'https://id.wompi.sv/connect/token';

    private string $clientId;
    private string $clientSecret;
    private float  $price;

    public function __construct()
    {
        $this->clientId     = config('services.wompi.client_id', '');
        $this->clientSecret = config('services.wompi.client_secret', '');
        $this->price        = (float) config('services.wompi.contract_price', 5.00);
    }

    // ─── OAuth2 Token ────────────────────────────────────────────────────────

    private function getAccessToken(): string
    {
        $http = Http::asForm()->timeout(10);

        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post($this->authUrl, [
            'grant_type'    => 'client_credentials',
            'audience'      => 'wompi_api',
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if ($response->failed()) {
            Log::error('WOMPI auth failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            throw new \RuntimeException('No se pudo autenticar con WOMPI. Verifica tus credenciales.');
        }

        return $response->json('access_token');
    }

    // ─── Create Payment Link ─────────────────────────────────────────────────

    public function createTransaction(Contract $contract): array
    {
        $payment = Payment::create([
            'contract_id' => $contract->id,
            'amount'      => $this->price,
            'currency'    => 'USD',
            'status'      => 'pending',
        ]);

        $token = $this->getAccessToken();

        $http = Http::withToken($token)->acceptJson()->asJson()->timeout(10);

        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        $response = $http->post("{$this->apiUrl}/EnlacePago", [
            'identificadorEnlaceComercio' => "contrato-{$contract->id}-pago-{$payment->id}",
            'monto'                       => $this->price,
            'nombreProducto'              => "Contrato de Arrendamiento #{$contract->id}",
            'formaPago'                   => [
                'permitirTarjetaCreditoDebido' => true,
                'permitirPagoConPuntoAgricola' => false,
                'permitirPagoEnCuotasAgricola' => false,
            ],
            'configuracion'               => [
                'urlRedirect'                => route('contracts.pay.return', $contract),
                'urlWebhook'                 => route('webhooks.wompi'),
                'notificarTransaccionCliente' => true,
                'esMontoEditable'            => false,
            ],
        ]);

        if ($response->failed()) {
            Log::error('WOMPI payment link creation failed', [
                'contract_id' => $contract->id,
                'status'      => $response->status(),
                'body'        => $response->json(),
            ]);
            $payment->update(['status' => 'failed']);
            throw new \RuntimeException('No se pudo iniciar el pago con WOMPI. Intenta de nuevo.');
        }

        $data = $response->json();

        Log::info('WOMPI payment link created', [
            'contract_id' => $contract->id,
            'idEnlace'    => $data['idEnlace'] ?? null,
            'urlEnlace'   => $data['urlEnlace'] ?? null,
        ]);

        $payment->update([
            'wompi_transaction_id' => isset($data['idEnlace']) ? 'enlace-' . $data['idEnlace'] : null,
        ]);

        return [
            'payment'      => $payment,
            'redirect_url' => $data['urlEnlace'] ?? null,
        ];
    }

    // ─── Verify Return URL signature ─────────────────────────────────────────

    /**
     * WOMPI SV sends back:
     *   identificadorEnlaceComercio, idTransaccion, idEnlace, monto, hash
     *
     * hash = HMAC-SHA256( identificador . idTransaccion . idEnlace . monto, client_secret )
     */
    public function verifyReturnHash(
        string $identificador,
        string $idTransaccion,
        string $idEnlace,
        string $monto,
        string $hash
    ): bool {
        if (empty($hash) || empty($idTransaccion)) {
            Log::warning('WOMPI return: missing hash or idTransaccion');
            return false;
        }

        $cadena   = $identificador . $idTransaccion . $idEnlace . $monto;
        $expected = hash_hmac('sha256', $cadena, $this->clientSecret);

        $valid = hash_equals($expected, strtolower($hash));

        if (! $valid) {
            Log::warning('WOMPI return: hash mismatch', [
                'cadena'   => $cadena,
                'expected' => $expected,
                'received' => $hash,
            ]);
        }

        return $valid;
    }

    // ─── Process Confirmed Return Payment ────────────────────────────────────

    public function processReturnPayment(Contract $contract, string $idTransaccion, string $idEnlace): void
    {
        $payment = $contract->payments()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (! $payment) {
            Log::warning('WOMPI return: no pending payment found', [
                'contract_id'   => $contract->id,
                'idTransaccion' => $idTransaccion,
            ]);
            return;
        }

        $payment->update([
            'wompi_transaction_id' => $idTransaccion ?: $payment->wompi_transaction_id,
            'status'               => 'completed',
            'paid_at'              => now(),
        ]);

        $contract->update(['status' => Contract::STATUS_PAID]);
        GenerateContractPdf::dispatch($contract);

        Log::info('WOMPI return: payment marked completed', [
            'contract_id'   => $contract->id,
            'payment_id'    => $payment->id,
            'idTransaccion' => $idTransaccion,
        ]);
    }

    // ─── Process Webhook ─────────────────────────────────────────────────────

    /**
     * WOMPI SV webhook sends the same fields as the return URL.
     * We use the same hash verification for security.
     */
    public function processWebhookPayment(array $payload): void
    {
        $identificador = (string) ($payload['identificadorEnlaceComercio'] ?? '');
        $idTransaccion = (string) ($payload['idTransaccion'] ?? '');
        $idEnlace      = (string) ($payload['idEnlace'] ?? '');
        $monto         = (string) ($payload['monto'] ?? '');
        $hash          = (string) ($payload['hash'] ?? '');

        Log::info('WOMPI webhook processing', compact('identificador', 'idTransaccion', 'idEnlace'));

        // Verify signature if hash is present
        if ($hash && ! $this->verifyReturnHash($identificador, $idTransaccion, $idEnlace, $monto, $hash)) {
            Log::warning('WOMPI webhook: invalid hash, skipping');
            return;
        }

        // Extract payment_id from our identifier: "contrato-{contract_id}-pago-{payment_id}"
        if (! preg_match('/pago-(\d+)$/', $identificador, $m)) {
            Log::warning('WOMPI webhook: cannot parse identificador', ['identificador' => $identificador]);
            return;
        }

        $payment = Payment::find((int) $m[1]);

        if (! $payment) {
            Log::warning('WOMPI webhook: payment not found', ['payment_id' => $m[1]]);
            return;
        }

        if ($payment->status === 'completed') {
            return; // already processed
        }

        $payment->update([
            'wompi_transaction_id' => $idTransaccion ?: $payment->wompi_transaction_id,
            'status'               => 'completed',
            'paid_at'              => now(),
        ]);

        $contract = $payment->contract;
        $contract->update(['status' => Contract::STATUS_PAID]);
        GenerateContractPdf::dispatch($contract);
    }
}
