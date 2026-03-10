<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Plantillas de Contrato') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Define los tipos de contrato disponibles y sus cláusulas predeterminadas') }}
            </p>
        </div>
        @if(!$showForm)
        <flux:button wire:click="openCreate" variant="primary" icon="plus">
            {{ __('Nueva Plantilla') }}
        </flux:button>
        @endif
    </div>

    {{-- Edit / Create Form --}}
    @if($showForm)
    <div class="mb-6 rounded-2xl border border-indigo-200 bg-indigo-50/50 dark:border-indigo-900/50 dark:bg-indigo-950/20 p-6">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-base font-semibold text-zinc-900 dark:text-white">
                {{ $editingId ? __('Editar Plantilla') : __('Nueva Plantilla') }}
            </h2>
            <button wire:click="cancel" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                <flux:icon name="x-mark" class="size-5" />
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
            <div>
                <flux:field>
                    <flux:label>{{ __('Nombre') }}</flux:label>
                    <flux:input wire:model="name" placeholder="Arrendamiento de Vivienda" />
                    <flux:error name="name" />
                </flux:field>
            </div>
            <div>
                <flux:field>
                    <flux:label>
                        Slug
                        <flux:badge size="sm" color="zinc" class="ml-1">{{ __('identificador único') }}</flux:badge>
                    </flux:label>
                    <flux:input wire:model="slug" placeholder="arrendamiento_vivienda" />
                    <flux:error name="slug" />
                </flux:field>
            </div>
            <div>
                <flux:field>
                    <flux:label>{{ __('Precio (USD)') }}</flux:label>
                    <flux:input wire:model="price" type="number" step="0.01" min="0" placeholder="5.00" />
                    <flux:error name="price" />
                </flux:field>
            </div>
            <div>
                <flux:field>
                    <flux:label>{{ __('Estado') }}</flux:label>
                    <div class="flex items-center gap-3 h-10">
                        <flux:switch wire:model="isActive" />
                        <span class="text-sm text-zinc-600 dark:text-zinc-400">
                            {{ $isActive ? __('Activa') : __('Inactiva') }}
                        </span>
                    </div>
                </flux:field>
            </div>
            <div class="sm:col-span-2">
                <flux:field>
                    <flux:label>{{ __('Descripción') }} <span class="text-zinc-400">({{ __('opcional') ?? 'opcional' }})</span></flux:label>
                    <flux:textarea wire:model="description" placeholder="Descripción breve del tipo de contrato…" rows="2" />
                </flux:field>
            </div>
        </div>

        {{-- Clauses editor --}}
        <div class="mb-5">
            <p class="mb-2 text-sm font-medium text-zinc-700 dark:text-zinc-300">
                {{ __('Cláusulas predeterminadas') }}
                <span class="ml-1 text-xs font-normal text-zinc-400">{{ __('(se cargarán automáticamente al crear un contrato)') }}</span>
            </p>

            @if(count($clauses) > 0)
            <div class="mb-3 space-y-2">
                @foreach($clauses as $i => $clause)
                <div class="flex items-start gap-2 rounded-xl border border-zinc-200 bg-white p-3 dark:border-zinc-700 dark:bg-zinc-900">
                    <span class="mt-0.5 flex size-5 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                        {{ $i + 1 }}
                    </span>
                    <p class="flex-1 text-sm text-zinc-700 dark:text-zinc-300">{{ $clause }}</p>
                    <button
                        wire:click="removeClause({{ $i }})"
                        class="flex-shrink-0 text-zinc-300 hover:text-red-500 dark:text-zinc-600 dark:hover:text-red-400 transition-colors"
                    >
                        <flux:icon name="x-mark" class="size-4" />
                    </button>
                </div>
                @endforeach
            </div>
            @endif

            <div class="flex gap-2">
                <flux:input
                    wire:model="newClause"
                    wire:keydown.enter.prevent="addClause"
                    :placeholder="__('Escribe una cláusula y presiona Enter o el botón…')"
                    class="flex-1"
                />
                <flux:button wire:click="addClause" variant="outline" icon="plus">
                    {{ __('Agregar') }}
                </flux:button>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button wire:click="cancel" variant="ghost">{{ __('Cancelar') }}</flux:button>
            <flux:button wire:click="save" variant="primary">
                {{ $editingId ? __('Guardar cambios') : __('Crear plantilla') }}
            </flux:button>
        </div>
    </div>
    @endif

    {{-- Templates list --}}
    @if($templates->isEmpty())
    <div class="rounded-2xl border border-dashed border-zinc-300 bg-white p-12 text-center dark:border-zinc-700 dark:bg-zinc-900">
        <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
            <flux:icon name="document-text" class="size-7 text-zinc-400" />
        </div>
        <p class="font-medium text-zinc-700 dark:text-zinc-300">{{ __('No hay plantillas creadas') }}</p>
        <p class="mt-1 text-sm text-zinc-400">{{ __('Crea la primera plantilla para definir los tipos de contrato disponibles.') }}</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($templates as $tpl)
        <div class="rounded-2xl border border-zinc-200 bg-white p-5 dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $tpl->name }}</h3>
                        <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-xs text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                            {{ $tpl->slug }}
                        </code>
                        @if($tpl->is_active)
                            <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                {{ __('Activa') }}
                            </span>
                        @else
                            <span class="rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                {{ __('Inactiva') }}
                            </span>
                        @endif
                    </div>
                    @if($tpl->description)
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-2">{{ $tpl->description }}</p>
                    @endif
                    <div class="flex flex-wrap items-center gap-4 text-xs text-zinc-400">
                        <span class="flex items-center gap-1">
                            <flux:icon name="currency-dollar" class="size-3.5" />
                            ${{ number_format($tpl->price, 2) }} USD
                        </span>
                        <span class="flex items-center gap-1">
                            <flux:icon name="list-bullet" class="size-3.5" />
                            {{ count($tpl->default_clauses ?? []) }} {{ __('cláusulas') }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <flux:button
                        wire:click="toggleActive({{ $tpl->id }})"
                        size="sm"
                        variant="ghost"
                        :title="$tpl->is_active ? __('Desactivar') : __('Activar')"
                    >
                        {{ $tpl->is_active ? __('Desactivar') : __('Activar') }}
                    </flux:button>
                    <flux:button
                        wire:click="openEdit({{ $tpl->id }})"
                        size="sm"
                        variant="outline"
                        icon="pencil"
                    >
                        {{ __('Editar') }}
                    </flux:button>
                    <flux:button
                        wire:click="delete({{ $tpl->id }})"
                        wire:confirm="{{ __('¿Eliminar esta plantilla? Esta acción no se puede deshacer.') }}"
                        size="sm"
                        variant="ghost"
                        icon="trash"
                        class="text-red-500 hover:text-red-700"
                    >
                    </flux:button>
                </div>
            </div>

            {{-- Clauses preview --}}
            @if(count($tpl->default_clauses ?? []) > 0)
            <div class="mt-4 border-t border-zinc-100 pt-4 dark:border-zinc-800">
                <p class="mb-2 text-xs font-medium text-zinc-400 uppercase tracking-wide">{{ __('Cláusulas') }}</p>
                <div class="space-y-1.5">
                    @foreach(array_slice($tpl->default_clauses, 0, 3) as $i => $clause)
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                        <span class="font-semibold text-zinc-400">{{ $i + 1 }}.</span> {{ Str::limit($clause, 120) }}
                    </p>
                    @endforeach
                    @if(count($tpl->default_clauses) > 3)
                    <p class="text-xs text-zinc-400 italic">
                        + {{ count($tpl->default_clauses) - 3 }} {{ __('cláusulas más') }}
                    </p>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif
</div>
