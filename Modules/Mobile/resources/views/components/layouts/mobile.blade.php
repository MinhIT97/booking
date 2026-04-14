<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>@yield('title', 'StayNest')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#ff385c',
                        'brand-dark': '#d90b2e',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        /* Hide scrollbars for a cleaner app look */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Safe area for mobile devices */
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
        .pt-safe { padding-top: env(safe-area-inset-top); }
        
        /* Smooth scrolling */
        html { scroll-behavior: smooth; }
        
        /* Prevent elastic scrolling in WebView if possible via CSS */
        body { overscroll-behavior-y: none; }
        
        .active-nav-item { color: #ff385c; }
    </style>
    @stack('css')
</head>
<body class="h-full flex flex-col text-gray-900 selection:bg-brand/20">

    {{-- Header --}}
    @if(View::hasSection('header'))
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 pt-safe">
            <div class="px-4 h-14 flex items-center justify-between">
                @yield('header')
            </div>
        </header>
    @endif

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto hide-scrollbar pb-24 @yield('main-class')">
        @yield('content')
    </main>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-50 pb-safe">
        <div class="flex items-center justify-around h-16">
            <a href="{{ route('mobile.home') }}" class="flex flex-col items-center gap-1 group">
                <svg class="w-6 h-6 {{ request()->routeIs('mobile.home') ? 'text-brand' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('mobile.home') ? 'text-brand' : 'text-gray-400' }}">Explore</span>
            </a>
            
            <a href="{{ route('mobile.bookings') }}" class="flex flex-col items-center gap-1 group">
                <svg class="w-6 h-6 {{ request()->routeIs('mobile.bookings') ? 'text-brand' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('mobile.bookings') ? 'text-brand' : 'text-gray-400' }}">Wishlists</span>
            </a>

            <a href="{{ route('mobile.bookings.index') }}" class="flex flex-col items-center gap-1 group">
                <svg class="w-6 h-6 {{ request()->routeIs('mobile.bookings.index') ? 'text-brand' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('mobile.bookings.index') ? 'text-brand' : 'text-gray-400' }}">Trips</span>
            </a>

            <a href="{{ route('mobile.profile') }}" class="flex flex-col items-center gap-1 group">
                <svg class="w-6 h-6 {{ request()->routeIs('mobile.profile') ? 'text-brand' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-[10px] font-medium {{ request()->routeIs('mobile.profile') ? 'text-brand' : 'text-gray-400' }}">Profile</span>
            </a>
        </div>
    </nav>

    @stack('scripts')
</body>
</html>
