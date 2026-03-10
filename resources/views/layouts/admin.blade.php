<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-950">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.header class="py-4">
                <div class="flex items-center gap-3 px-1 min-w-0">
                    <div class="flex size-8 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-600 dark:bg-indigo-500">
                        <flux:icon name="shield-check" class="size-4 text-white" />
                    </div>
                    <div class="leading-tight min-w-0">
                        <p class="text-sm font-bold text-zinc-900 dark:text-white truncate">Admin Panel</p>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ config('app.name') }}</p>
                    </div>
                </div>
                <flux:sidebar.collapse class="lg:hidden ml-auto" />
            </flux:sidebar.header>

            <flux:sidebar.nav class="mt-1">
                <flux:sidebar.group :heading="__('General')">
                    <flux:sidebar.item icon="squares-2x2" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>
                        Dashboard
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Gestión')">
                    <flux:sidebar.item icon="users" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>
                        {{ __('Usuarios') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="credit-card" :href="route('admin.payments')" :current="request()->routeIs('admin.payments')" wire:navigate>
                        {{ __('Pagos') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                <flux:sidebar.group :heading="__('Configuración')">
                    <flux:sidebar.item icon="document-text" :href="route('admin.templates')" :current="request()->routeIs('admin.templates')" wire:navigate>
                        {{ __('Plantillas') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('admin.settings')" :current="request()->routeIs('admin.settings')" wire:navigate>
                        {{ __('Configuración') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            @include('partials.theme')

            <div class="p-2 border-t border-zinc-200 dark:border-zinc-800">
                <flux:sidebar.item icon="arrow-left-start-on-rectangle" :href="route('dashboard')" wire:navigate>
                    {{ __('Volver a la App') }}
                </flux:sidebar.item>
            </div>

            <x-desktop-user-menu class="hidden lg:block" />
        </flux:sidebar>

        <!-- Mobile Header -->
        <flux:header class="lg:hidden border-b border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <div class="flex items-center gap-2 ml-2">
                <div class="flex size-6 items-center justify-center rounded bg-indigo-600">
                    <flux:icon name="shield-check" class="size-3 text-white" />
                </div>
                <span class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Admin Panel</span>
            </div>
            <flux:spacer />
        </flux:header>

        <flux:main>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </div>
        </flux:main>

        @fluxScripts
    </body>
</html>
