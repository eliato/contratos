<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
                <flux:navbar.item icon="document-text" :href="route('contracts.create')" :current="request()->routeIs('contracts.*')" wire:navigate>
                    {{ __('Contratos') }}
                </flux:navbar.item>
                @auth
                @if(auth()->user()->is_admin)
                <flux:navbar.item icon="shield-check" :href="route('admin.dashboard')" :current="request()->routeIs('admin.*')" wire:navigate>
                    {{ __('Admin') }}
                </flux:navbar.item>
                @endif
                @endauth
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
            @include('partials.theme')
            </flux:navbar>

            {{-- Language switcher --}}
            <div class="flex items-center gap-0.5 mr-2">
                <a href="{{ route('lang.switch', 'es') }}"
                   class="px-2 py-1 text-xs font-bold rounded transition-colors
                          {{ app()->getLocale() === 'es'
                              ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-100'
                              : 'text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200' }}">
                    ES
                </a>
                <span class="text-zinc-300 dark:text-zinc-600 text-xs select-none">|</span>
                <a href="{{ route('lang.switch', 'en') }}"
                   class="px-2 py-1 text-xs font-bold rounded transition-colors
                          {{ app()->getLocale() === 'en'
                              ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-100'
                              : 'text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200' }}">
                    EN
                </a>
            </div>

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="document-text" :href="route('contracts.create')" :current="request()->routeIs('contracts.*')" wire:navigate>
                        {{ __('Contratos') }}
                    </flux:sidebar.item>
                    @auth
                    @if(auth()->user()->is_admin)
                    <flux:sidebar.item icon="shield-check" :href="route('admin.dashboard')" :current="request()->routeIs('admin.*')" wire:navigate>
                        {{ __('Admin') }}
                    </flux:sidebar.item>
                    @endif
                    @endauth
                </flux:sidebar.group>
            </flux:sidebar.nav>

        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
