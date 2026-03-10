<div>
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <flux:heading size="xl">{{ __('Mis Contratos') }}</flux:heading>
        <flux:button href="{{ route('contracts.create') }}" wire:navigate variant="primary" icon="plus">
            {{ __('Nuevo Contrato') }}
        </flux:button>
    </div>

    {{-- Flash messages --}}
    @if(session('payment'))
        <div class="mb-4 p-4 rounded-xl border border-lime-300 bg-lime-50 dark:bg-lime-900/20 dark:border-lime-700 text-sm text-lime-800 dark:text-lime-300">
            {{ session('payment') }}
        </div>
    @endif

    {{-- Empty state --}}
    @if ($contracts->isEmpty())
        <div class="flex flex-col items-center justify-center py-24 text-center">
            <div class="mb-4 rounded-full bg-zinc-100 dark:bg-zinc-800 p-5">
                <flux:icon name="document-text" class="size-10 text-zinc-400" />
            </div>
            <p class="text-lg font-medium text-zinc-700 dark:text-zinc-300">{{ __('Sin contratos aún') }}</p>
            <p class="mt-1 text-sm text-zinc-500">{{ __('Crea tu primer contrato con el botón de arriba.') }}</p>
        </div>
    @else
        {{-- Stats bar --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            @php
                $total    = $contracts->count();
                $pending  = $contracts->where('status', 'pending_payment')->count();
                $paid     = $contracts->where('status', 'paid')->count();
                $signed   = $contracts->where('status', 'signed')->count();
            @endphp
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 px-4 py-3">
                <p class="text-xs text-zinc-500 mb-0.5">{{ __('Total') }}</p>
                <p class="text-2xl font-bold text-zinc-800 dark:text-white">{{ $total }}</p>
            </div>
            <div class="rounded-xl border border-yellow-200 dark:border-yellow-800/50 bg-yellow-50 dark:bg-yellow-900/10 px-4 py-3">
                <p class="text-xs text-yellow-600 dark:text-yellow-500 mb-0.5">{{ __('Pendiente Pago') }}</p>
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-400">{{ $pending }}</p>
            </div>
            <div class="rounded-xl border border-blue-200 dark:border-blue-800/50 bg-blue-50 dark:bg-blue-900/10 px-4 py-3">
                <p class="text-xs text-blue-600 dark:text-blue-500 mb-0.5">{{ __('Pagados') }}</p>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400">{{ $paid }}</p>
            </div>
            <div class="rounded-xl border border-lime-200 dark:border-lime-800/50 bg-lime-50 dark:bg-lime-900/10 px-4 py-3">
                <p class="text-xs text-lime-600 dark:text-lime-500 mb-0.5">{{ __('Firmados') }}</p>
                <p class="text-2xl font-bold text-lime-700 dark:text-lime-400">{{ $signed }}</p>
            </div>
        </div>

        {{-- Cards grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($contracts as $contract)
                @php
                    $badgeColor = match($contract->status) {
                        'pending_payment' => 'yellow',
                        'paid'            => 'blue',
                        'signed'          => 'lime',
                        default           => 'zinc',
                    };
                    $badgeLabel = match($contract->status) {
                        'pending_payment' => __('Pendiente Pago'),
                        'paid'            => __('Pagado'),
                        'signed'          => __('Firmado'),
                        default           => __('Borrador'),
                    };
                @endphp
                <div class="group relative rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    {{-- Top row: name + badge --}}
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <h2 class="text-base font-semibold text-zinc-900 dark:text-white leading-tight capitalize">
                            {{ $contract->tenant_name }}
                        </h2>
                        <flux:badge :color="$badgeColor" size="sm" class="shrink-0">{{ $badgeLabel }}</flux:badge>
                    </div>

                    {{-- Landlord --}}
                    <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-1">{{ __('Arrendador') }}: {{ $contract->landlord_name }}</p>

                    {{-- Address --}}
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4 line-clamp-2 flex-1">
                        {{ $contract->property_address }}
                    </p>

                    {{-- Bottom row: canon + date --}}
                    <div class="flex items-center justify-between text-sm text-zinc-600 dark:text-zinc-400 border-t border-zinc-100 dark:border-zinc-800 pt-3">
                        <span class="font-medium">{{ __('Canon') }}: ${{ number_format($contract->rent_amount, 2) }}</span>
                        <span class="text-xs">{{ $contract->start_date->format('d/n/Y') }}</span>
                    </div>

                    {{-- Action buttons (appear on hover) --}}
                    <div class="absolute top-3 right-3 hidden group-hover:flex items-center gap-1 bg-white dark:bg-zinc-900 rounded-lg p-0.5 shadow-sm border border-zinc-100 dark:border-zinc-700">
                        @if($contract->status === 'pending_payment')
                            <a href="{{ route('contracts.pay', $contract) }}"
                               class="flex items-center justify-center size-7 rounded-md text-zinc-400 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors"
                               title="{{ __('Pagar') }}">
                                <flux:icon name="credit-card" class="size-4" />
                            </a>
                        @endif
                        @if($contract->isPaid())
                            <a href="{{ route('contracts.sign', $contract) }}" wire:navigate
                               class="flex items-center justify-center size-7 rounded-md text-zinc-400 hover:text-lime-600 hover:bg-lime-50 dark:hover:bg-lime-900/20 transition-colors"
                               title="{{ __('Firmar contrato') }}">
                                <flux:icon name="pencil-square" class="size-4" />
                            </a>
                        @endif
                        @if($contract->isDownloadable())
                            <a href="{{ route('contracts.download', $contract) }}"
                               target="_blank"
                               class="flex items-center justify-center size-7 rounded-md text-zinc-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                               title="{{ __('Descargar PDF') }}">
                                <flux:icon name="arrow-down-tray" class="size-4" />
                            </a>
                        @endif
                        <button
                            wire:click="confirmDelete({{ $contract->id }})"
                            class="flex items-center justify-center size-7 rounded-md text-zinc-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                            title="{{ __('Eliminar contrato') }}">
                            <flux:icon name="trash" class="size-4" />
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Delete confirmation modal --}}
    <flux:modal name="delete-contract" wire:model="showDeleteModal" class="max-w-md w-full">
        <div class="p-6">
            {{-- Icon --}}
            <div class="flex items-center justify-center size-12 rounded-full bg-red-100 dark:bg-red-900/30 mx-auto mb-4">
                <flux:icon name="trash" class="size-6 text-red-600 dark:text-red-400" />
            </div>

            {{-- Title --}}
            <h3 class="text-center text-base font-semibold text-zinc-900 dark:text-white mb-1">
                {{ __('Eliminar contrato') }}
            </h3>

            {{-- Body --}}
            <p class="text-center text-sm text-zinc-500 dark:text-zinc-400 mb-1">
                {{ __('Estás a punto de eliminar el contrato de') }}
            </p>
            <p class="text-center text-sm font-semibold text-zinc-800 dark:text-zinc-200 capitalize mb-4">
                {{ $deletingName }}
            </p>
            <p class="text-center text-xs text-zinc-400 dark:text-zinc-500 mb-6">
                {{ __('Esta acción es permanente y no se puede deshacer.') }}
            </p>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <flux:button wire:click="cancelDelete" variant="ghost" class="flex-1">
                    {{ __('Cancelar') }}
                </flux:button>
                <flux:button wire:click="delete" variant="danger" class="flex-1">
                    {{ __('Sí, eliminar') }}
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>

@script
<script>
    document.addEventListener('livewire:navigate', () => {
        $wire.showDeleteModal = false;
        $wire.deletingId = null;
        $wire.deletingName = '';
    });
</script>
@endscript
