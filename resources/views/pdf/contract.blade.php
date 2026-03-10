<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Contrato de Arrendamiento #{{ $contract->id }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'DejaVu Sans', 'Helvetica', sans-serif;
        font-size: 10.5pt;
        line-height: 1.65;
        color: #1e293b;
    }
    /* Page wrapper — dompdf respects padding on a block wrapper better than @page margins */
    .page {
        padding: 56pt 50pt 50pt 50pt; /* top 2cm, sides 1.76cm (~50pt) */
    }
    /* Header */
    .doc-header {
        text-align: center;
        border-bottom: 2px solid #1e293b;
        padding-bottom: 12px;
        margin-bottom: 20px;
    }
    .doc-header h1 {
        font-size: 15pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .doc-header .subtitle {
        font-size: 9pt;
        color: #64748b;
    }
    /* Sections */
    .section {
        margin-bottom: 18px;
    }
    .section-title {
        font-size: 10pt;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #334155;
        border-bottom: 1px solid #cbd5e1;
        padding-bottom: 4px;
        margin-bottom: 10px;
    }
    /* Parties table */
    .parties-table {
        width: 100%;
        border-collapse: collapse;
    }
    .parties-table td {
        width: 50%;
        vertical-align: top;
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .parties-table td:first-child {
        border-right: none;
    }
    .parties-label {
        font-weight: bold;
        font-size: 9pt;
        text-transform: uppercase;
        color: #475569;
        margin-bottom: 6px;
    }
    .field-row {
        margin-bottom: 4px;
        font-size: 10pt;
    }
    .field-label {
        font-weight: bold;
        color: #475569;
    }
    /* Economics grid */
    .econ-table {
        width: 100%;
        border-collapse: collapse;
    }
    .econ-table td {
        width: 25%;
        padding: 8px 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        text-align: center;
    }
    .econ-value {
        font-size: 13pt;
        font-weight: bold;
        color: #0f172a;
    }
    .econ-label {
        font-size: 8.5pt;
        color: #64748b;
        margin-top: 2px;
    }
    /* Clauses */
    .clause {
        padding: 6px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 10pt;
        text-align: justify;
    }
    .clause:last-child { border-bottom: none; }
    /* Legal disclaimer */
    .legal-box {
        background: #fef9c3;
        border: 1px solid #fde047;
        padding: 10px 14px;
        margin-top: 16px;
        font-size: 8.5pt;
        color: #713f12;
    }
    .legal-box strong { color: #92400e; }
    /* Signature block */
    .sig-section {
        margin-top: 32px;
        page-break-inside: avoid;
    }
    .sig-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }
    .sig-table td {
        width: 50%;
        vertical-align: bottom;
        padding: 0 20px;
        text-align: center;
    }
    .sig-image {
        width: 180px;
        height: 70px;
        display: block;
        margin: 0 auto 4px auto;
    }
    .sig-line {
        border-top: 1px solid #334155;
        padding-top: 8px;
        margin-top: 8px;
        font-size: 9pt;
        color: #475569;
        line-height: 1.5;
    }
    .sig-empty {
        height: 70px;
    }
    /* Metadata footer */
    .meta-footer {
        margin-top: 20px;
        font-size: 8pt;
        color: #94a3b8;
        text-align: center;
        border-top: 1px solid #e2e8f0;
        padding-top: 8px;
    }
</style>
</head>
<body>
<div class="page">

{{-- ── Header ──────────────────────────────────────────────────────── --}}
<div class="doc-header">
    <h1>Contrato Privado de Arrendamiento de Vivienda</h1>
    <p class="subtitle">República de El Salvador &nbsp;|&nbsp; Generado el {{ now()->format('d \d\e F \d\e Y') }}</p>
</div>

{{-- ── Partes contratantes ─────────────────────────────────────────── --}}
<div class="section">
    <p class="section-title">Partes Contratantes</p>
    <table class="parties-table">
        <tr>
            <td>
                <p class="parties-label">Arrendador</p>
                <div class="field-row"><span class="field-label">Nombre:</span> {{ $contract->landlord_name }}</div>
                <div class="field-row"><span class="field-label">DUI:</span> {{ $contract->landlord_dui }}</div>
                <div class="field-row"><span class="field-label">Domicilio:</span> {{ $contract->landlord_address }}</div>
            </td>
            <td>
                <p class="parties-label">Arrendatario</p>
                <div class="field-row"><span class="field-label">Nombre:</span> {{ $contract->tenant_name }}</div>
                <div class="field-row"><span class="field-label">DUI:</span> {{ $contract->tenant_dui }}</div>
                <div class="field-row"><span class="field-label">Domicilio:</span> {{ $contract->tenant_address }}</div>
            </td>
        </tr>
    </table>
</div>

{{-- ── Inmueble ──────────────────────────────────────────────────────── --}}
<div class="section">
    <p class="section-title">Objeto del Contrato</p>
    <p>El <strong>Arrendador</strong> da en arrendamiento al <strong>Arrendatario</strong> el inmueble de uso habitacional ubicado en:</p>
    <p style="margin-top: 6px; font-weight: bold; font-size: 11pt;">{{ $contract->property_address }}</p>
</div>

{{-- ── Condiciones económicas ─────────────────────────────────────── --}}
<div class="section">
    <p class="section-title">Condiciones Económicas</p>
    <table class="econ-table">
        <tr>
            <td>
                <div class="econ-value">${{ number_format($contract->rent_amount, 2) }}</div>
                <div class="econ-label">Canon mensual (USD)</div>
            </td>
            <td>
                <div class="econ-value">${{ number_format($contract->deposit_amount, 2) }}</div>
                <div class="econ-label">Depósito de garantía</div>
            </td>
            <td>
                <div class="econ-value">{{ $contract->duration_months }}</div>
                <div class="econ-label">Meses de duración</div>
            </td>
            <td>
                <div class="econ-value">{{ $contract->start_date->format('d/m/Y') }}</div>
                <div class="econ-label">Fecha de inicio</div>
            </td>
        </tr>
    </table>
</div>

{{-- ── Plazo ───────────────────────────────────────────────────────── --}}
<div class="section">
    <p class="section-title">Plazo del Contrato</p>
    <p>
        El presente contrato tendrá una vigencia de <strong>{{ $contract->duration_months }} meses</strong>,
        iniciando el <strong>{{ $contract->start_date->format('d/m/Y') }}</strong>
        y finalizando el <strong>{{ $contract->end_date->format('d/m/Y') }}</strong>.
        El contrato podrá renovarse de mutuo acuerdo entre las partes con treinta (30) días de anticipación.
    </p>
</div>

{{-- ── Cláusulas del Contrato ──────────────────────────────────────── --}}
<div class="section">
    <p class="section-title">Cláusulas del Contrato</p>
    @php
        $ordinals = ['Primera','Segunda','Tercera','Cuarta','Quinta','Sexta','Séptima','Octava','Novena','Décima','Undécima','Duodécima','Decimotercera','Decimocuarta','Decimoquinta'];

        // Use clauses stored in the contract; fall back to hardcoded defaults for legacy contracts
        $clauses = !empty($contract->custom_clauses) ? $contract->custom_clauses : [
            'El Arrendatario se obliga a pagar el canon de arrendamiento puntualmente dentro de los primeros cinco (5) días hábiles de cada mes calendario.',
            'El inmueble objeto de este contrato será destinado exclusivamente para uso habitacional, quedando expresamente prohibido su uso comercial o industrial.',
            'Queda prohibido al Arrendatario subarrendar total o parcialmente el inmueble, ni ceder sus derechos y obligaciones derivados de este contrato sin autorización escrita previa del Arrendador.',
            'Al vencimiento del plazo, el Arrendatario deberá entregar el inmueble en el mismo estado en que lo recibió, salvo el desgaste natural por el uso ordinario.',
            'Las reparaciones menores necesarias para el mantenimiento del inmueble serán a cargo del Arrendatario; las reparaciones mayores o estructurales corresponden al Arrendador.',
            'El depósito de garantía será devuelto al Arrendatario al término del contrato, previa inspección del inmueble y deducción de daños si los hubiere.',
            'Para todo lo no previsto en el presente contrato, las partes se someten a las leyes de la República de El Salvador.',
        ];
    @endphp
    @foreach($clauses as $i => $clause)
        @php $label = $ordinals[$i] ?? (($i + 1) . '.°'); @endphp
        <div class="clause"><strong>{{ $label }}.</strong> {{ $clause }}</div>
    @endforeach
</div>

{{-- ── Disclaimer legal ────────────────────────────────────────────── --}}
<div class="legal-box">
    <strong>Aviso Legal Importante:</strong>
    Este es un contrato privado de arrendamiento generado digitalmente mediante la plataforma ContratoSaaS.
    <strong>Este documento no constituye certificación notarial ni firma electrónica certificada</strong>
    conforme a la legislación vigente de El Salvador.
    Para efectos legales que requieran fe pública, deberá ser autenticado ante Notario.
    Contrato ID: <strong>#{{ $contract->id }}</strong>.
</div>

{{-- ── Bloque de firmas ────────────────────────────────────────────── --}}
<div class="sig-section">
    <p class="section-title" style="margin-top: 0;">Firmas</p>

    <table class="sig-table">
        <tr>
            <td>
                @if($signed && $contract->landlordSignature)
                    @php
                        $disk    = \Illuminate\Support\Facades\Storage::disk('local');
                        $sigPath = $contract->landlordSignature->signature_path;
                        $lData   = $disk->exists($sigPath)
                            ? 'data:image/png;base64,' . base64_encode($disk->get($sigPath))
                            : null;
                    @endphp
                    @if($lData)
                        <img src="{{ $lData }}" class="sig-image" alt="Firma Arrendador" />
                    @else
                        <div class="sig-empty"></div>
                    @endif
                @else
                    <div class="sig-empty"></div>
                @endif
                <div class="sig-line">
                    <strong>{{ $contract->landlord_name }}</strong><br>
                    Arrendador &nbsp;·&nbsp; DUI: {{ $contract->landlord_dui }}<br>
                    @if($signed && $contract->landlordSignature)
                        Firmado: {{ $contract->landlordSignature->signed_at->format('d/m/Y H:i') }}<br>
                        IP: {{ $contract->landlordSignature->ip_address }}
                    @endif
                </div>
            </td>
            <td>
                @if($signed && $contract->tenantSignature)
                    @php
                        $disk    = \Illuminate\Support\Facades\Storage::disk('local');
                        $sigPath = $contract->tenantSignature->signature_path;
                        $tData   = $disk->exists($sigPath)
                            ? 'data:image/png;base64,' . base64_encode($disk->get($sigPath))
                            : null;
                    @endphp
                    @if($tData)
                        <img src="{{ $tData }}" class="sig-image" alt="Firma Arrendatario" />
                    @else
                        <div class="sig-empty"></div>
                    @endif
                @else
                    <div class="sig-empty"></div>
                @endif
                <div class="sig-line">
                    <strong>{{ $contract->tenant_name }}</strong><br>
                    Arrendatario &nbsp;·&nbsp; DUI: {{ $contract->tenant_dui }}<br>
                    @if($signed && $contract->tenantSignature)
                        Firmado: {{ $contract->tenantSignature->signed_at->format('d/m/Y H:i') }}<br>
                        IP: {{ $contract->tenantSignature->ip_address }}
                    @endif
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- ── Footer de metadatos ─────────────────────────────────────────── --}}
<div class="meta-footer">
    Generado por ContratoSaaS &nbsp;|&nbsp; ID #{{ $contract->id }} &nbsp;|&nbsp;
    @if($contract->pdf_hash) SHA-256: {{ substr($contract->pdf_hash, 0, 16) }}... @endif &nbsp;|&nbsp;
    {{ now()->format('d/m/Y H:i') }} UTC
</div>

</div>{{-- end .page --}}
</body>
</html>
