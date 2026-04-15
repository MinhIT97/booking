{{-- Modules/Host/resources/views/components/sidebar.blade.php --}}

@php
$navItems = [
['route' => 'host.bookings.index', 'icon' => 'calendar', 'label' => 'Bookings'],
['route' => 'host.earnings.index', 'icon' => 'chart', 'label' => 'Earnings'],
['route' => 'host.reviews.index', 'icon' => 'star', 'label' => 'Reviews'],
['route' => 'host.settings.index', 'icon' => 'cog', 'label' => 'Settings'],
];

// Dropdown: Properties group
$propertyRoutes = ['host.properties.index', 'host.properties.create'];
$isPropertyActive = request()->routeIs('host.properties.*');
@endphp

<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-sidebar flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300">

    {{-- Logo --}}
    <div class="h-16 flex items-center gap-3 px-6 border-b border-white/5 flex-shrink-0">
        <svg class="w-7 h-7 text-brand" fill="currentColor" viewBox="0 0 24 24">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
        </svg>
        <span class="text-lg font-bold text-white">Stay<span class="text-brand">Nest</span></span>
        <span class="ml-auto text-xs text-slate-500 font-medium bg-white/5 px-2 py-0.5 rounded-full">Host</span>
    </div>

    {{-- Host profile snippet --}}
    <div class="flex items-center gap-3 px-5 py-4 mx-3 mt-4 bg-white/5 rounded-2xl">
        <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center text-brand font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name ?? 'H', 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Host' }}</p>
            <p class="text-slate-400 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <p class="px-4 text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Menu</p>

        {{-- Dashboard --}}
        @php
        $dashActive = request()->routeIs('host.dashboard');
        $dashUrl = Route::has('host.dashboard') ? route('host.dashboard') : '#';
        @endphp
        <a href="{{ $dashUrl }}" class="nav-link {{ $dashActive ? 'active' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
            @if ($dashActive)
            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-brand"></span>
            @endif
        </a>

        {{-- ── Properties dropdown ── --}}
        <div x-data="{ open: {{ $isPropertyActive ? 'true' : 'false' }} }">
            {{-- Trigger --}}
            <button @click="open = !open"
                class="nav-link w-full {{ $isPropertyActive ? 'active' : '' }}">
                {{-- building icon --}}
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Properties</span>
                {{-- chevron --}}
                <svg class="w-3.5 h-3.5 ml-auto transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Sub-items --}}
            <div x-show="open"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1"
                class="mt-0.5 ml-4 space-y-0.5 border-l border-white/10 pl-3">

                {{-- My Properties --}}
                @php $listActive = request()->routeIs('host.properties.index'); @endphp
                <a href="{{ Route::has('host.properties.index') ? route('host.properties.index') : '#' }}"
                    class="nav-link text-[13px] py-2 {{ $listActive ? 'active' : '' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span>My Properties</span>
                    @if ($listActive)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-brand"></span>
                    @endif
                </a>

                {{-- Add Property --}}
                @php $createActive = request()->routeIs('host.properties.create'); @endphp
                <a href="{{ Route::has('host.properties.create') ? route('host.properties.create') : '#' }}"
                    class="nav-link text-[13px] py-2 {{ $createActive ? 'active' : '' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Add Property</span>
                    @if ($createActive)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-brand"></span>
                    @endif
                </a>
            </div>
        </div>

        {{-- Other nav items --}}
        @foreach ($navItems as $item)
        @php
        $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*');
        $routeExists = Route::has($item['route']);
        $url = $routeExists ? route($item['route']) : '#';
        @endphp

        <a href="{{ $url }}"
            class="nav-link {{ $isActive ? 'active' : '' }}"
            @if(!$routeExists) onclick="event.preventDefault()" @endif>

            @switch($item['icon'])
            @case('calendar')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            @break
            @case('chart')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            @break
            @case('star')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            @break
            @case('cog')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            @break
            @endswitch

            <span>{{ $item['label'] }}</span>

            @if ($isActive)
            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-brand"></span>
            @endif
        </a>
        @endforeach
    </nav>

    {{-- Bottom logout --}}
    <div class="p-3 border-t border-white/5">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="nav-link w-full text-left text-red-400 hover:text-red-300 hover:bg-red-500/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Log out</span>
            </button>
        </form>
    </div>
</aside>