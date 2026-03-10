<div class="max-w-2xl mx-auto py-6 px-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-zinc-800 dark:hover:text-zinc-200">Dashboard</a>
        <flux:icon name="chevron-right" class="size-3.5" />
        <span class="text-zinc-800 dark:text-zinc-200">{{ __('Firmar Contrato') }} #{{ $contract->id }}</span>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <flux:heading size="xl">{{ __('Firma del Contrato') }}</flux:heading>
        <flux:subheading>
            {{ $contract->tenant_name }} — {{ $contract->property_address }}
        </flux:subheading>
    </div>

    {{-- Payment message (flash) --}}
    @if(session('payment_message'))
        <div class="mb-4 p-4 rounded-xl border border-lime-300 bg-lime-50 dark:bg-lime-900/20 dark:border-lime-700 text-sm text-lime-800 dark:text-lime-300">
            {{ session('payment_message') }}
        </div>
    @endif

    {{-- Signature status indicators --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div @class([
            'rounded-xl border p-3.5 text-center text-sm transition-colors',
            'border-lime-400 bg-lime-50 dark:bg-lime-900/20 dark:border-lime-700' => $landlordSignature,
            'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900' => !$landlordSignature,
        ])>
            @if($landlordSignature)
                <flux:icon name="check-circle" class="size-5 text-lime-500 mx-auto mb-1" />
                <p class="font-semibold text-lime-700 dark:text-lime-400">{{ __('Arrendador firmó') }}</p>
                <p class="text-xs text-zinc-500 mt-0.5">{{ $landlordSignature->signed_at->format('d/m/Y H:i') }}</p>
            @else
                <flux:icon name="clock" class="size-5 text-zinc-400 mx-auto mb-1" />
                <p class="font-medium text-zinc-500">{{ __('Arrendador') }}</p>
                <p class="text-xs text-zinc-400 mt-0.5">{{ __('Pendiente') }}</p>
            @endif
        </div>
        <div @class([
            'rounded-xl border p-3.5 text-center text-sm transition-colors',
            'border-lime-400 bg-lime-50 dark:bg-lime-900/20 dark:border-lime-700' => $tenantSignature,
            'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900' => !$tenantSignature,
        ])>
            @if($tenantSignature)
                <flux:icon name="check-circle" class="size-5 text-lime-500 mx-auto mb-1" />
                <p class="font-semibold text-lime-700 dark:text-lime-400">{{ __('Arrendatario firmó') }}</p>
                <p class="text-xs text-zinc-500 mt-0.5">{{ $tenantSignature->signed_at->format('d/m/Y H:i') }}</p>
            @else
                <flux:icon name="clock" class="size-5 text-zinc-400 mx-auto mb-1" />
                <p class="font-medium text-zinc-500">{{ __('Arrendatario') }}</p>
                <p class="text-xs text-zinc-400 mt-0.5">{{ __('Pendiente') }}</p>
            @endif
        </div>
    </div>

    @if($allSigned)
        {{-- Both signed --}}
        <div class="rounded-2xl border border-lime-300 bg-lime-50 dark:bg-lime-900/20 dark:border-lime-700 p-8 text-center"
             @if(!$pdfReady) wire:poll.3s="refreshState" @endif>
            <div class="flex items-center justify-center size-14 rounded-full bg-lime-500 mx-auto mb-4">
                <flux:icon name="check" class="size-7 text-white" />
            </div>
            <flux:heading size="lg" class="mb-2">{{ __('¡Contrato completamente firmado!') }}</flux:heading>
            @if($pdfReady)
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
                    {{ __('Ambas partes han firmado. Tu documento está listo para descargar.') }}
                </p>
                <flux:button href="{{ route('contracts.download', $contract) }}" target="_blank" variant="primary" icon="arrow-down-tray">
                    {{ __('Descargar PDF Firmado') }}
                </flux:button>
            @else
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                    {{ __('Ambas partes han firmado. El PDF con las firmas se está generando…') }}
                </p>
                <div class="flex items-center justify-center gap-2 text-xs text-zinc-400">
                    <svg class="animate-spin size-4 text-lime-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    {{ __('Verificando automáticamente…') }}
                </div>
            @endif
        </div>
    @else
        {{-- Canvas signing UI --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    {{ __('Firmando como:') }}
                    <span class="text-lime-600 dark:text-lime-400">
                        {{ $signerRole === 'landlord'
                            ? __('Arrendador') . ' — ' . $contract->landlord_name
                            : __('Arrendatario') . ' — ' . $contract->tenant_name }}
                    </span>
                </p>
            </div>

            <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-3">{{ __('Dibuja tu firma en el área de abajo con el mouse o con el dedo.') }}</p>

            <div class="relative rounded-xl overflow-hidden border-2 border-dashed border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-950 mb-4"
                 style="touch-action: none;">
                <canvas id="signature-canvas"
                        width="560" height="200"
                        class="w-full block"
                        style="cursor: crosshair; touch-action: none;">
                </canvas>
                <div id="sig-placeholder" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <span class="text-zinc-300 dark:text-zinc-700 text-sm select-none">{{ __('Firma aquí') }}</span>
                </div>
            </div>

            @error('signatureData')
                <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
            @enderror

            <div class="flex gap-3">
                <flux:button wire:click="clearCanvas" variant="ghost" icon="arrow-path" class="flex-1">
                    {{ __('Borrar') }}
                </flux:button>
                <flux:button wire:click="saveSignature" variant="primary" icon="check" class="flex-1"
                             wire:loading.attr="disabled" wire:target="saveSignature">
                    <span wire:loading.remove wire:target="saveSignature">{{ __('Guardar Firma') }}</span>
                    <span wire:loading wire:target="saveSignature">{{ __('Guardando...') }}</span>
                </flux:button>
            </div>
        </div>

        <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-4 text-center">
            {{ __('Al guardar la firma confirmas que eres') }}
            {{ $signerRole === 'landlord'
                ? $contract->landlord_name . ' (' . __('Arrendador') . ')'
                : $contract->tenant_name . ' (' . __('Arrendatario') . ')' }}
            {{ __('y aceptas el contenido del contrato.') }}
        </p>
    @endif

</div>

@script
<script>
    const canvas  = document.getElementById('signature-canvas');
    const ctx     = canvas.getContext('2d');
    const holder  = document.getElementById('sig-placeholder');
    let drawing   = false;
    let lastX = 0, lastY = 0;
    let hasMark   = false;

    function scalePos(e) {
        const rect  = canvas.getBoundingClientRect();
        const scaleX = canvas.width  / rect.width;
        const scaleY = canvas.height / rect.height;
        const src    = e.touches ? e.touches[0] : e;
        return {
            x: (src.clientX - rect.left)  * scaleX,
            y: (src.clientY  - rect.top)  * scaleY,
        };
    }

    function startDraw(e) {
        e.preventDefault();
        drawing = true;
        const p = scalePos(e);
        lastX = p.x; lastY = p.y;
        if (holder) holder.style.display = 'none';
    }

    function draw(e) {
        e.preventDefault();
        if (!drawing) return;
        const p = scalePos(e);
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(p.x, p.y);
        ctx.strokeStyle = document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#1e293b';
        ctx.lineWidth   = 2.5;
        ctx.lineCap     = 'round';
        ctx.lineJoin    = 'round';
        ctx.stroke();
        lastX = p.x; lastY = p.y;
        hasMark = true;
    }

    function endDraw(e) {
        e.preventDefault();
        if (!drawing) return;
        drawing = false;
        if (hasMark) {
            @this.set('signatureData', canvas.toDataURL('image/png'));
        }
    }

    canvas.addEventListener('mousedown',  startDraw);
    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('mousemove',  draw);
    canvas.addEventListener('touchmove',  draw,       { passive: false });
    canvas.addEventListener('mouseup',    endDraw);
    canvas.addEventListener('touchend',   endDraw,    { passive: false });
    canvas.addEventListener('mouseleave', endDraw);

    $wire.on('clear-canvas', () => {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasMark = false;
        if (holder) holder.style.display = '';
        @this.set('signatureData', '');
    });
</script>
@endscript
