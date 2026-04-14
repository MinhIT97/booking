{{-- Modules/Host/resources/views/components/navbar.blade.php --}}
<header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 gap-4 sticky top-0 z-20 shadow-sm">

    {{-- Mobile hamburger --}}
    <button id="menu-btn"
            class="lg:hidden w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-600 hover:border-brand hover:text-brand transition-colors"
            aria-label="Open menu">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Breadcrumb --}}
    <nav class="hidden sm:flex items-center gap-2 text-sm text-gray-400 min-w-0">
        <a href="{{ route('host.dashboard') }}" class="hover:text-brand transition-colors font-medium">Dashboard</a>
        @hasSection('breadcrumb')
            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-600 font-medium truncate">@yield('breadcrumb')</span>
        @endif
    </nav>

    {{-- Right side --}}
    <div class="flex items-center gap-3 ml-auto">

        {{-- Search --}}
        <div class="hidden md:flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 w-56">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" placeholder="Search…"
                   class="flex-1 text-sm bg-transparent focus:outline-none text-gray-700 placeholder-gray-400" />
        </div>

        {{-- Notifications bell --}}
        <button class="relative w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:border-brand hover:text-brand transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            {{-- Unread badge (conditionally render) --}}
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-brand rounded-full"></span>
        </button>

        {{-- View site link --}}
        <a href="/" target="_blank"
           class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-gray-600 border border-gray-200 px-3 py-2 rounded-xl hover:border-brand hover:text-brand transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View site
        </a>

        {{-- Avatar dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                    class="flex items-center gap-2 border border-gray-200 rounded-xl py-1.5 px-2.5 hover:border-brand transition-colors">
                <div class="w-7 h-7 rounded-full bg-brand text-white text-xs font-bold flex items-center justify-center">
                    {{ strtoupper(substr(auth()->user()->name ?? 'H', 0, 1)) }}
                </div>
                <span class="hidden sm:block text-sm font-medium text-gray-700 max-w-[100px] truncate">
                    {{ auth()->user()->name ?? 'Host' }}
                </span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>

    </div>
</header>
