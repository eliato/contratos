<div class="max-w-2xl mx-auto py-6 px-4">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-zinc-800 dark:hover:text-zinc-200">Dashboard</a>
        <flux:icon name="chevron-right" class="size-3.5" />
        <span class="text-zinc-800 dark:text-zinc-200">{{ __('Nuevo Contrato') }}</span>
    </div>

    {{-- Step progress --}}
    <div class="flex items-center mb-8">
        @foreach([__('Arrendador'), __('Arrendatario'), __('Inmueble'), __('Cláusulas'), __('Vista Previa')] as $i => $label)
            @php $num = $i + 1; $active = $num === $step; $done = $num < $step; @endphp
            <div class="flex flex-col items-center gap-1.5 min-w-0">
                <div @class([
                    'size-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-200 cursor-default',
                    'bg-lime-500 text-white shadow-md shadow-lime-200 dark:shadow-lime-900' => $active,
                    'bg-lime-400 dark:bg-lime-600 text-white' => $done,
                    'bg-zinc-200 dark:bg-zinc-700 text-zinc-500 dark:text-zinc-400' => !$active && !$done,
                ])>
                    @if($done)
                        <flux:icon name="check" class="size-4" />
                    @else
                        {{ $num }}
                    @endif
                </div>
                <span @class([
                    'text-xs font-medium whitespace-nowrap hidden sm:block',
                    'text-lime-600 dark:text-lime-400' => $active,
                    'text-zinc-400 dark:text-zinc-500' => !$active,
                ])>{{ $label }}</span>
            </div>
            @if($i < 4)
                <div @class([
                    'flex-1 h-0.5 mx-2 transition-colors duration-300',
                    'bg-lime-400 dark:bg-lime-600' => $done,
                    'bg-zinc-200 dark:bg-zinc-700' => !$done,
                ])></div>
            @endif
        @endforeach
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm p-6 sm:p-8">

        {{-- ── Step 1: Arrendador ─────────────────────────────────── --}}
        @if($step === 1)
            <flux:heading size="lg" class="mb-1">{{ __('Datos del Arrendador') }}</flux:heading>
            <flux:subheading class="mb-6">{{ __('Información del propietario del inmueble.') }}</flux:subheading>
            <div class="space-y-4">
                <flux:input wire:model="landlord_name" :label="__('Nombre completo')" placeholder="Ej: Juan Alberto García" required />
                <div x-data x-init="window.duiMask($el)">
                    <flux:input wire:model.blur="landlord_dui" label="DUI" placeholder="00000000-0" required />
                </div>
                <flux:input wire:model="landlord_address" :label="__('Dirección de residencia')" placeholder="Ej: Col. Escalón, Pasaje X #5, San Salvador" required />
            </div>
        @endif

        {{-- ── Step 2: Arrendatario ──────────────────────────────── --}}
        @if($step === 2)
            <flux:heading size="lg" class="mb-1">{{ __('Datos del Arrendatario') }}</flux:heading>
            <flux:subheading class="mb-6">{{ __('Información de la persona que arrendará el inmueble.') }}</flux:subheading>
            <div class="space-y-4">
                <flux:input wire:model="tenant_name" :label="__('Nombre completo')" placeholder="Ej: María Elena Pérez" required />
                <div x-data x-init="window.duiMask($el)">
                    <flux:input wire:model.blur="tenant_dui" label="DUI" placeholder="00000000-0" required />
                </div>
                <flux:input wire:model="tenant_address" :label="__('Dirección actual de residencia')" placeholder="Ej: Res. Los Héroes, #12, Soyapango" required />
            </div>
        @endif

        {{-- ── Step 3: Inmueble ──────────────────────────────────── --}}
        @if($step === 3)
            <flux:heading size="lg" class="mb-1">{{ __('Datos del Inmueble') }}</flux:heading>
            <flux:subheading class="mb-6">{{ __('Condiciones del arrendamiento.') }}</flux:subheading>
            <div class="space-y-4">
                <flux:input wire:model="property_address" :label="__('Dirección del inmueble a arrendar')" placeholder="Ej: Calle Arce #88, Col. Médica, San Salvador" required />
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="rent_amount" type="number" step="0.01" min="1" :label="__('Canon mensual')" prefix="$" placeholder="0.00" required />
                    <flux:input wire:model="deposit_amount" type="number" step="0.01" min="0" :label="__('Depósito de garantía')" prefix="$" placeholder="0.00" required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="duration_months" type="number" min="1" max="120" :label="__('Duración (meses)')" placeholder="12" required />
                    <flux:input wire:model="start_date" type="date" :label="__('Fecha de inicio')" required />
                </div>
            </div>
        @endif

        {{-- ── Step 4: Cláusulas ─────────────────────────────────── --}}
        @if($step === 4)
            <flux:heading size="lg" class="mb-1">{{ __('Cláusulas Adicionales') }}</flux:heading>
            <flux:subheading class="mb-3">{{ __('Agrega condiciones especiales al contrato (opcional).') }}</flux:subheading>

            {{-- Info: standard clauses already included --}}
            @if($templateClausesCount > 0)
            <div class="flex items-start gap-3 p-3 mb-5 rounded-xl bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900">
                <flux:icon name="information-circle" class="size-5 text-blue-500 dark:text-blue-400 shrink-0 mt-0.5" />
                <p class="text-sm text-blue-700 dark:text-blue-300">
                    {{ __('El contrato ya incluye :count cláusulas estándar de la plantilla.', ['count' => $templateClausesCount]) }}
                    {{ __('Aquí solo necesitas agregar condiciones especiales propias del arrendamiento.') }}
                </p>
            </div>
            @endif

            {{-- User's extra clauses --}}
            @if(count($custom_clauses) > 0)
                <div class="space-y-2 mb-4">
                    @foreach($custom_clauses as $i => $clause)
                        <div class="flex items-start gap-3 p-3 bg-zinc-50 dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
                            <span class="shrink-0 size-5 rounded-full bg-lime-100 dark:bg-lime-900/40 text-lime-700 dark:text-lime-400 text-xs font-bold flex items-center justify-center mt-0.5">{{ $i + 1 }}</span>
                            <p class="flex-1 text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $clause }}</p>
                            <button wire:click="removeClause({{ $i }})"
                                class="shrink-0 size-6 flex items-center justify-center rounded-lg text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <flux:icon name="x-mark" class="size-3.5" />
                            </button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-zinc-400 dark:text-zinc-500 mb-4 italic">{{ __('Sin cláusulas adicionales. Puedes continuar sin agregar.') }}</p>
            @endif

            <div class="flex gap-2">
                <flux:input
                    wire:model="new_clause"
                    wire:keydown.enter.prevent="addClause"
                    :placeholder="__('Escribe una cláusula especial y presiona Agregar...')"
                    class="flex-1" />
                <flux:button wire:click="addClause" variant="outline" icon="plus">{{ __('Agregar') }}</flux:button>
            </div>
        @endif

        {{-- ── Step 5: Vista Previa ──────────────────────────────── --}}
        @if($step === 5)
            <flux:heading size="lg" class="mb-1">{{ __('Vista Previa del Contrato') }}</flux:heading>
            <flux:subheading class="mb-6">{{ __('Revisa toda la información antes de confirmar.') }}</flux:subheading>

            <div class="space-y-3 text-sm">
                {{-- Arrendador --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4">
                    <p class="font-semibold text-zinc-400 dark:text-zinc-500 uppercase text-xs tracking-wider mb-3">{{ __('Arrendador') }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1">
                        <div><span class="text-zinc-500">{{ __('Nombre') }}</span><p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $landlord_name }}</p></div>
                        <div><span class="text-zinc-500">DUI</span><p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $landlord_dui }}</p></div>
                        <div><span class="text-zinc-500">{{ __('Dirección') }}</span><p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $landlord_address }}</p></div>
                    </div>
                </div>

                {{-- Arrendatario --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4">
                    <p class="font-semibold text-zinc-400 dark:text-zinc-500 uppercase text-xs tracking-wider mb-3">{{ __('Arrendatario') }}</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-1">
                        <div><span class="text-zinc-500">{{ __('Nombre') }}</span><p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $tenant_name }}</p></div>
                        <div><span class="text-zinc-500">DUI</span><p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $tenant_dui }}</p></div>
                        <div><span class="text-zinc-500">{{ __('Dirección') }}</span><p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $tenant_address }}</p></div>
                    </div>
                </div>

                {{-- Inmueble --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4">
                    <p class="font-semibold text-zinc-400 dark:text-zinc-500 uppercase text-xs tracking-wider mb-3">{{ __('Inmueble') }}</p>
                    <div class="mb-2"><span class="text-zinc-500">{{ __('Dirección') }}</span><p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $property_address }}</p></div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2">
                        <div><span class="text-zinc-500 block">{{ __('Canon') }}</span><span class="font-semibold text-zinc-800 dark:text-zinc-200">${{ number_format((float)$rent_amount, 2) }}</span></div>
                        <div><span class="text-zinc-500 block">{{ __('Depósito') }}</span><span class="font-semibold text-zinc-800 dark:text-zinc-200">${{ number_format((float)$deposit_amount, 2) }}</span></div>
                        <div><span class="text-zinc-500 block">{{ __('Duración') }}</span><span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $duration_months }} {{ __('meses') }}</span></div>
                        <div><span class="text-zinc-500 block">{{ __('Inicio') }}</span><span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $start_date ? \Carbon\Carbon::parse($start_date)->format('d/m/Y') : '—' }}</span></div>
                    </div>
                </div>

                {{-- Cláusulas --}}
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4">
                    <p class="font-semibold text-zinc-400 dark:text-zinc-500 uppercase text-xs tracking-wider mb-3">{{ __('Cláusulas') }}</p>
                    @if($templateClausesCount > 0)
                        <div class="flex items-center gap-2 mb-2">
                            <flux:icon name="check-circle" class="size-4 text-lime-500 shrink-0" />
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                <strong class="text-zinc-700 dark:text-zinc-300">{{ $templateClausesCount }}</strong> {{ __('cláusulas estándar incluidas automáticamente') }}
                            </p>
                        </div>
                    @endif
                    @if(count($custom_clauses) > 0)
                        <div class="mt-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                            <p class="text-xs text-zinc-400 mb-1.5">{{ __('Cláusulas adicionales:') }}</p>
                            @foreach($custom_clauses as $i => $clause)
                                <p class="text-zinc-600 dark:text-zinc-400 text-sm mb-1">{{ $i + 1 }}. {{ $clause }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if($templateClausesCount === 0 && count($custom_clauses) === 0)
                        <p class="text-sm text-zinc-400 italic">{{ __('Sin cláusulas.') }}</p>
                    @endif
                </div>
            </div>

            {{-- Disclaimer --}}
            <div class="mt-5 p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-xl">
                <div class="flex gap-3">
                    <flux:icon name="exclamation-triangle" class="size-5 text-amber-600 dark:text-amber-500 shrink-0 mt-0.5" />
                    <div class="text-sm text-amber-800 dark:text-amber-300">
                        <p class="font-semibold mb-1">{{ __('Aviso legal') }}</p>
                        <p>{{ __('Este es un contrato privado de arrendamiento sin certificación notarial ni firma electrónica certificada. Al confirmar, serás redirigido a pagar') }} <strong>${{ number_format((float)config('services.wompi.contract_price', 5.00), 2) }} USD</strong> {{ __('para generar el PDF.') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ── Payment error ──────────────────────────────────── --}}
        @error('payment')
            <div class="mt-5 p-4 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 rounded-xl flex gap-3">
                <flux:icon name="x-circle" class="size-5 text-red-500 shrink-0 mt-0.5" />
                <p class="text-sm text-red-700 dark:text-red-400">{{ $message }}</p>
            </div>
        @enderror

        {{-- ── Navigation buttons ────────────────────────────────── --}}
        <div class="flex items-center justify-between mt-8 pt-6 border-t border-zinc-100 dark:border-zinc-800">
            <div>
                @if($step > 1)
                    <flux:button wire:click="previousStep" variant="ghost" icon="arrow-left">{{ __('Anterior') }}</flux:button>
                @else
                    <flux:button href="{{ route('dashboard') }}" wire:navigate variant="ghost" icon="arrow-left">{{ __('Cancelar') }}</flux:button>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ __('Paso :step de :total', ['step' => $step, 'total' => $totalSteps]) }}</span>
                @if($step < $totalSteps)
                    <flux:button wire:click="nextStep" variant="primary" icon-trailing="arrow-right">{{ __('Siguiente') }}</flux:button>
                @else
                    <flux:button wire:click="submit" variant="primary" icon="credit-card"
                                 wire:loading.attr="disabled" wire:target="submit"
                                 :disabled="$submitted">
                        @if($submitted)
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin size-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                {{ __('Procesando...') }}
                            </span>
                        @else
                            <span wire:loading.remove wire:target="submit">{{ __('Confirmar y Pagar') }}</span>
                            <span wire:loading wire:target="submit" class="flex items-center gap-2">
                                <svg class="animate-spin size-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                {{ __('Procesando...') }}
                            </span>
                        @endif
                    </flux:button>
                @endif
            </div>
        </div>
    </div>
</div>

@script
<script>
    window.duiMask = function (el) {
        const input = el.querySelector('input');
        if (!input) return;

        input.maxLength = 10;

        // Block any key that is not a digit or a navigation/control key
        input.addEventListener('keydown', e => {
            const nav = ['Backspace', 'Delete', 'Tab', 'Enter', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
            if (!nav.includes(e.key) && !/^\d$/.test(e.key) && !e.ctrlKey && !e.metaKey) {
                e.preventDefault();
            }
        });

        // Format in real-time: ########-#  (auto-insert dash after 8th digit)
        input.addEventListener('input', e => {
            const digits    = e.target.value.replace(/\D/g, '').slice(0, 9);
            const formatted = digits.length > 8
                ? digits.slice(0, 8) + '-' + digits.slice(8)
                : digits;

            if (e.target.value !== formatted) {
                e.target.value = formatted;
            }
        });
    };
</script>
@endscript
