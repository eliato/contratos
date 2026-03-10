<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Services\WompiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(private WompiService $wompi) {}

    /**
     * Initiate payment — creates WOMPI payment link and redirects user to payment page.
     */
    public function initiate(Contract $contract)
    {
        abort_if($contract->user_id !== auth()->id(), 403);
        abort_unless($contract->status === Contract::STATUS_PENDING_PAYMENT, 422, __('El contrato no está en estado de pago pendiente.'));

        try {
            $result = $this->wompi->createTransaction($contract);
        } catch (\RuntimeException $e) {
            return redirect()->route('dashboard')->withErrors(['payment' => $e->getMessage()]);
        }

        if (empty($result['redirect_url'])) {
            return redirect()->route('dashboard')->withErrors(['payment' => __('No se obtuvo URL de pago de WOMPI.')]);
        }

        return redirect()->away($result['redirect_url']);
    }

    /**
     * WOMPI redirects back here after the user completes/cancels payment.
     *
     * WOMPI SV sends: identificadorEnlaceComercio, idTransaccion, idEnlace, monto, hash
     * Success is verified via HMAC-SHA256(identificador.idTransaccion.idEnlace.monto, client_secret)
     */
    public function return(Request $request, Contract $contract)
    {
        abort_if($contract->user_id !== auth()->id(), 403);

        $identificador = (string) $request->query('identificadorEnlaceComercio', '');
        $idTransaccion = (string) $request->query('idTransaccion', '');
        $idEnlace      = (string) $request->query('idEnlace', '');
        $monto         = (string) $request->query('monto', '');
        $hash          = (string) $request->query('hash', '');

        Log::info('WOMPI return URL hit', [
            'contract_id'   => $contract->id,
            'identificador' => $identificador,
            'idTransaccion' => $idTransaccion,
            'idEnlace'      => $idEnlace,
            'monto'         => $monto,
            'hash_present'  => ! empty($hash),
            'all_params'    => $request->query(),
        ]);

        $approved = $this->wompi->verifyReturnHash($identificador, $idTransaccion, $idEnlace, $monto, $hash);

        if ($approved) {
            $this->wompi->processReturnPayment($contract, $idTransaccion, $idEnlace);

            return redirect()->route('contracts.show', $contract)
                ->with('payment_message', __('Pago realizado con éxito. Tu contrato está siendo generado.'));
        }

        return redirect()->route('dashboard')
            ->with('payment_failed', __('El pago no fue completado o no se pudo verificar. Intenta de nuevo desde el dashboard.'));
    }

    /**
     * WOMPI SV webhook — receives transaction notifications.
     */
    public function webhook(Request $request)
    {
        Log::info('WOMPI webhook received', ['payload' => $request->all()]);

        $this->wompi->processWebhookPayment($request->all());

        return response()->json(['received' => true]);
    }
}
