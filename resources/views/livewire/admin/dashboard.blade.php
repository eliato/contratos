<div>
    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Dashboard</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Resumen general del sistema') }}</p>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        {{-- Total users --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">{{ __('Usuarios') }}</p>
                <div class="flex size-9 items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                    <flux:icon name="users" class="size-4 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
            <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalUsers }}</p>
            <p class="mt-1 text-xs text-zinc-400">
                @if($newUsersThisMonth > 0)
                    <span class="text-emerald-500">+{{ $newUsersThisMonth }}</span> {{ __('este mes') }}
                @else
                    {{ __('Sin nuevos este mes') }}
                @endif
            </p>
        </div>

        {{-- Total contracts --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">{{ __('Contratos') }}</p>
                <div class="flex size-9 items-center justify-center rounded-xl bg-violet-100 dark:bg-violet-900/30">
                    <flux:icon name="document-text" class="size-4 text-violet-600 dark:text-violet-400" />
                </div>
            </div>
            <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $totalContracts }}</p>
            <p class="mt-1 text-xs text-zinc-400">{{ $contractsByStatus->get('signed', 0) }} {{ __('firmados') }}</p>
        </div>

        {{-- Total revenue --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">{{ __('Ingresos') }}</p>
                <div class="flex size-9 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-900/30">
                    <flux:icon name="banknotes" class="size-4 text-emerald-600 dark:text-emerald-400" />
                </div>
            </div>
            <p class="text-3xl font-bold text-zinc-900 dark:text-white">${{ number_format($totalRevenue, 2) }}</p>
            <p class="mt-1 text-xs text-zinc-400">
                @if($revenueThisMonth > 0)
                    <span class="text-emerald-500">+${{ number_format($revenueThisMonth, 2) }}</span> {{ __('este mes') }}
                @else
                    $0.00 {{ __('este mes') }}
                @endif
            </p>
        </div>

        {{-- Pending payment --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="mb-3 flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">{{ __('Por Cobrar') }}</p>
                <div class="flex size-9 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-900/30">
                    <flux:icon name="clock" class="size-4 text-amber-600 dark:text-amber-400" />
                </div>
            </div>
            <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ $contractsByStatus->get('pending_payment', 0) }}</p>
            <p class="mt-1 text-xs text-zinc-400">{{ __('pendientes de pago') }}</p>
        </div>
    </div>

    {{-- Bottom section --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Status breakdown --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
            <h3 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Por Estado') }}</h3>
            <div class="space-y-3">
                @foreach([
                    ['status' => 'draft',           'label' => __('Borrador'),       'dot' => 'bg-zinc-400'],
                    ['status' => 'pending_payment', 'label' => __('Pago Pendiente'), 'dot' => 'bg-amber-400'],
                    ['status' => 'paid',            'label' => __('Pagados'),        'dot' => 'bg-blue-500'],
                    ['status' => 'signed',          'label' => __('Firmados'),       'dot' => 'bg-emerald-500'],
                ] as $item)
                @php $count = $contractsByStatus->get($item['status'], 0); @endphp
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="size-2 rounded-full {{ $item['dot'] }}"></div>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ $item['label'] }}</span>
                    </div>
                    <span class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent contracts --}}
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
            <h3 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-white">{{ __('Contratos Recientes') }}</h3>
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($recentContracts as $contract)
                <div class="flex items-center justify-between py-2.5 first:pt-0 last:pb-0">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-zinc-800 dark:text-zinc-200">
                            {{ $contract->tenant_name }}
                        </p>
                        <p class="text-xs text-zinc-400">
                            {{ $contract->user->name }} &middot; {{ $contract->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="ml-4 flex-shrink-0 rounded-full px-2 py-0.5 text-xs font-medium
                        @if($contract->status === 'signed')          bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                        @elseif($contract->status === 'paid')        bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                        @elseif($contract->status === 'pending_payment') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400
                        @else                                        bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400
                        @endif">
                        {{ match($contract->status) {
                            'signed'          => __('Firmado'),
                            'paid'            => __('Pagado'),
                            'pending_payment' => __('Pago Pendiente'),
                            default           => __('Borrador'),
                        } }}
                    </span>
                </div>
                @empty
                <p class="py-6 text-center text-sm text-zinc-400">{{ __('No hay contratos aún.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
