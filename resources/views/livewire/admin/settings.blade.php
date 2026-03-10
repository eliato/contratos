<div>
    {{-- Page header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Configuración') }}</h1>
        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Parámetros generales del sistema') }}</p>
    </div>

    <div class="max-w-xl">
        <div class="rounded-2xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 p-6 space-y-6">

            {{-- Session lifetime --}}
            <div>
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">
                    {{ __('Tiempo de sesión') }}
                </h2>
                <p class="text-xs text-zinc-400 dark:text-zinc-500 mb-3">
                    {{ __('Minutos de INACTIVIDAD antes de que la sesión expire (no desde el login). Cada acción del usuario reinicia el contador.') }}
                </p>
                <div class="mb-4 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-xs text-zinc-600 dark:text-zinc-400">
                    <flux:icon name="clock" class="size-3.5 text-zinc-400" />
                    {{ __('Valor activo en este momento') }}:
                    <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ config('session.lifetime') }} min</span>
                </div>

                <div class="flex items-end gap-4">
                    <div class="w-48">
                        <flux:field>
                            <flux:label>{{ __('Minutos') }}</flux:label>
                            <flux:input
                                wire:model="sessionLifetime"
                                type="number"
                                min="5"
                                max="43200"
                                placeholder="120"
                            />
                            <flux:error name="sessionLifetime" />
                        </flux:field>
                    </div>
                    <div class="pb-0.5 text-sm text-zinc-400 dark:text-zinc-500 leading-10">
                        ≈ {{ round($sessionLifetime / 60, 1) }} {{ $sessionLifetime < 120 ? __('hora') : __('horas') }}
                    </div>
                </div>

                {{-- Presets --}}
                <div class="flex flex-wrap gap-2 mt-3">
                    @foreach([
                        ['label' => '30 min',  'value' => 30],
                        ['label' => '1 h',     'value' => 60],
                        ['label' => '2 h',     'value' => 120],
                        ['label' => '8 h',     'value' => 480],
                        ['label' => '24 h',    'value' => 1440],
                        ['label' => '7 días',  'value' => 10080],
                    ] as $preset)
                        <button
                            wire:click="$set('sessionLifetime', {{ $preset['value'] }})"
                            @class([
                                'px-3 py-1 rounded-full text-xs font-medium transition-colors',
                                'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' => $sessionLifetime == $preset['value'],
                                'bg-zinc-100 text-zinc-500 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-700' => $sessionLifetime != $preset['value'],
                            ])
                        >
                            {{ $preset['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-zinc-100 dark:border-zinc-800"></div>

            {{-- Actions --}}
            <div class="flex items-center gap-4">
                <flux:button wire:click="save" variant="primary" icon="check">
                    {{ __('Guardar') }}
                </flux:button>

                @if($saved)
                    <div class="flex items-center gap-1.5 text-sm text-emerald-600 dark:text-emerald-400"
                         x-data x-init="setTimeout(() => $el.style.display = 'none', 3000)">
                        <flux:icon name="check-circle" class="size-4" />
                        {{ __('Guardado') }}
                    </div>
                @endif
            </div>

        </div>

        {{-- Info box --}}
        <div class="mt-4 p-4 rounded-xl bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 text-xs text-blue-700 dark:text-blue-300 flex gap-3">
            <flux:icon name="information-circle" class="size-4 shrink-0 mt-0.5" />
            <div class="space-y-1.5">
                <p>
                    {{ __('El valor guardado aquí sobrescribe') }} <code class="font-mono bg-blue-100 dark:bg-blue-900/50 px-1 rounded">SESSION_LIFETIME</code> {{ __('del .env en tiempo de ejecución.') }}
                </p>
                <p class="font-semibold">
                    {{ __('La sesión expira por INACTIVIDAD, no por tiempo transcurrido desde el login. Si el usuario hace clic o navega, el contador se reinicia.') }}
                </p>
                <p>
                    {{ __('Para probar: cierra el navegador sin hacer clic y espera el tiempo configurado, o borra manualmente la cookie de sesión.') }}
                </p>
            </div>
        </div>
    </div>
</div>
