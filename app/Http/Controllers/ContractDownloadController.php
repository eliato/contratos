<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Support\Facades\Storage;

class ContractDownloadController extends Controller
{
    public function __invoke(Contract $contract)
    {
        abort_if($contract->user_id !== auth()->id(), 403);
        abort_unless($contract->isDownloadable(), 403, 'El contrato no está disponible para descarga.');
        abort_if(empty($contract->pdf_path), 404, 'El archivo PDF no está listo aún.');

        $url = Storage::disk('s3')->temporaryUrl(
            $contract->pdf_path,
            now()->addMinutes(5)
        );

        return redirect($url);
    }
}
