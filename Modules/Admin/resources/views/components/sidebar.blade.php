{{-- Modules/Admin/resources/views/components/sidebar.blade.php --}}

@php
$navItems = [
['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'home', 'label' => 'Dashboard'],
['route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'users', 'label' => 'User Management'],
['route' => 'admin.properties.index', 'active' => 'admin.properties.*', 'icon' => 'building', 'label' => 'Properties'],
['route' => 'admin.bookings.index', 'active' => 'admin.bookings.*', 'icon' => 'calendar', 'label' => 'Bookings'],
['route' => 'admin.settings.index', 'active' => 'admin.settings.*', 'icon' => 'cog', 'label' => 'Settings'],
];
@endphp

<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-sidebar flex flex-col -translate-x-full lg:translate-x-0 transition-transform duration-300">

    {{-- Logo --}}
    <div class="h-16 flex items-center gap-3 px-6 border-b border-white/5 flex-shrink-0">
        <svg class="w-7 h-7 text-brand" fill="currentColor" viewBox="0 0 24 24">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
        </svg>
        <span class="text-lg font-bold text-white">Stay<span class="text-brand">Nest</span></span>
        <span class="ml-auto text-xs text-slate-500 font-medium bg-white/5 px-2 py-0.5 rounded-full">Admin</span>
    </div>

    {{-- Admin profile snippet --}}
    <div class="flex items-center gap-3 px-5 py-4 mx-3 mt-4 bg-white/5 rounded-2xl">
        <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center text-brand font-bold text-sm flex-shrink-0">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
            <p class="text-slate-400 text-xs truncate">{{ auth()->user()->email ?? '' }}</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <p class="px-4 text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Menu</p>

        @foreach ($navItems as $item)
        @php
        $isActive = request()->routeIs($item['active']);
        $routeExists = Route::has($item['route']);
        $url = $routeExists ? route($item['route']) : '#';
        @endphp

        <a href="{{ $url }}"
            class="nav-link {{ $isActive ? 'active' : '' }}"
            @if(!$routeExists) onclick="event.preventDefault()" @endif>

            {{-- Icons --}}
            @switch($item['icon'])
            @case('home')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10h14V10" />
            </svg>
            @break
            @case('users')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            @break
            @case('building')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            @break
            @case('calendar')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            @break
            @case('cog')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Log out</span>
            </button>
        </form>
    </div>
</aside>
