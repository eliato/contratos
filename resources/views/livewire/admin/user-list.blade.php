<div>
    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ __('Usuarios') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Gestiona los usuarios registrados en el sistema') }}</p>
        </div>
        <div class="w-full sm:w-72">
            <flux:input
                wire:model.live.debounce.300ms="search"
                :placeholder="__('Buscar por nombre o email…')"
                icon="magnifying-glass"
            />
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 dark:border-zinc-800">
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">#</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Usuario') }}</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-400 hidden md:table-cell">{{ __('Registrado') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Contratos') }}</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Rol') }}</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-zinc-400">{{ __('Acciones') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse($users as $user)
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td class="px-4 py-3 text-zinc-400 text-xs">{{ $user->id }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <flux:avatar :name="$user->name" :initials="$user->initials()" class="size-8" />
                            <div class="min-w-0">
                                <p class="font-medium text-zinc-900 dark:text-white truncate">{{ $user->name }}</p>
                                <p class="text-xs text-zinc-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-zinc-500 dark:text-zinc-400 hidden md:table-cell">
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                            {{ $user->contracts_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        @if($user->is_admin)
                            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                <flux:icon name="shield-check" class="size-3" />
                                Admin
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                {{ __('Usuario') }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if($user->id !== auth()->id())
                            <flux:button
                                wire:click="toggleAdmin({{ $user->id }})"
                                wire:loading.attr="disabled"
                                size="sm"
                                variant="{{ $user->is_admin ? 'ghost' : 'outline' }}"
                            >
                                {{ $user->is_admin ? __('Quitar admin') : __('Hacer admin') }}
                            </flux:button>
                        @else
                            <span class="text-xs text-zinc-400 italic">{{ __('Tú') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-sm text-zinc-400">
                        {{ __('No se encontraron usuarios.') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="border-t border-zinc-100 px-4 py-3 dark:border-zinc-800">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
