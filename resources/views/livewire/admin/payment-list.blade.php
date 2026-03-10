<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Pagos') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Historial de transacciones procesadas') }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <flux:input
                wire:model.live.debounce.300ms="search"
                :placeholder="__('Buscar usuario o transacción…')"
                icon="magnifying-glass"
                class="w-64"
            />
            <flux:select wire:model.live="statusFilter" class="w-40">
                <flux:select.option value="">{{ __('Todos los estados') }}</flux:select.option>
                <flux:select.option value="pending">{{ __('Pendiente') }}</flux:select.option>
                <flux:select.option value="completed">{{ __('Completado') }}</flux:select.option>
                <flux:select.option value="failed">{{ __('Fallido') }}</flux:select.option>
            </flux:select>
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-800">
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Usuario') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 hidden md:table-cell">{{ __('Contrato') }}</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Monto') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Estado') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 hidden lg:table-cell">{{ __('Transacción') }}</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-zinc-400 hidden sm:table-cell">{{ __('Fecha') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($payments as $payment)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3">
                        @if($payment->contract?->user)
                        <p class="font-medium text-zinc-800 dark:text-zinc-200">{{ $payment->contract->user->name }}</p>
                        <p class="text-xs text-zinc-400">{{ $payment->contract->user->email }}</p>
                        @else
                        <span class="text-zinc-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        @if($payment->contract)
                        <p class="text-zinc-700 dark:text-zinc-300">{{ $payment->contract->tenant_name }}</p>
                        <p class="text-xs text-zinc-400">#{{ $payment->contract_id }}</p>
                        @else
                        <span class="text-zinc-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-zinc-800 dark:text-zinc-200">
                        ${{ number_format($payment->amount, 2) }}
                        <span class="block text-xs font-normal text-zinc-400">{{ $payment->currency }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-medium
                            @if($payment->status === 'completed') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                            @elseif($payment->status === 'failed') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                            @else bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                            @endif">
                            {{ match($payment->status) {
                                'completed' => __('Completado'),
                                'failed'    => __('Fallido'),
                                default     => __('Pendiente'),
                            } }}
                        </span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        @if($payment->wompi_transaction_id)
                        <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-xs text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                            {{ Str::limit($payment->wompi_transaction_id, 20) }}
                        </code>
                        @else
                        <span class="text-zinc-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right text-xs text-zinc-400 hidden sm:table-cell">
                        {{ $payment->paid_at?->format('d/m/Y H:i') ?? $payment->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-sm text-zinc-400">
                        {{ __('No se encontraron pagos.') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($payments->hasPages())
        <div class="border-t border-zinc-100 px-4 py-3 dark:border-zinc-800">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
