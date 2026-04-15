<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>@yield('title', 'Host Dashboard') — StayNest</title>
    <meta name="description" content="@yield('meta_description', 'Manage your rental properties on StayNest.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN — swap for compiled asset in production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#ff385c',
                            dark: '#d90b2e',
                            light: '#ffe4e8'
                        },
                        sidebar: {
                            DEFAULT: '#0f172a',
                            text: '#94a3b8',
                            active: '#ff385c'
                        },
                    },
                }
            }
        }
    </script>

    <style>
        /* Sidebar transition */
        #sidebar {
            transition: transform .3s cubic-bezier(.4, 0, .2, 1);
        }

        /* nav-link — plain CSS equivalent of the @apply utilities */
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #94a3b8;
            transition: color .15s ease, background .15s ease;
            text-decoration: none;
        }

        .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, .05);
        }

        .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, .10);
            font-weight: 600;
        }

        .nav-link.active svg {
            color: #ff385c;
        }

        /* Stat card */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
            border: 1px solid #f3f4f6;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #ff385c, #d90b2e);
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 14px;
            transition: all .3s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 24px rgba(255, 56, 92, .35);
            transform: translateY(-1px);
        }

        .btn-outline {
            border: 2px solid #e5e7eb;
            color: #374151;
            font-weight: 600;
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 14px;
            transition: border-color .2s, color .2s;
            cursor: pointer;
            background: transparent;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-outline:hover {
            border-color: #ff385c;
            color: #ff385c;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full font-sans antialiased bg-gray-50 text-gray-900">

    <div class="flex h-full min-h-screen">

        {{-- ═══ SIDEBAR ═══ --}}
        @include('host::components.sidebar')

        {{-- Mobile sidebar overlay --}}
        <div id="sidebar-overlay"
            class="fixed inset-0 bg-black/50 z-30 lg:hidden hidden"
            onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); this.classList.add('hidden')">
        </div>

        {{-- ═══ MAIN AREA ═══ --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Top Navbar --}}
            @include('host::components.navbar')

            {{-- Flash messages --}}
            @if (session('success'))
            <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3 flex items-center gap-2" role="alert">
                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3 flex items-center gap-2" role="alert">
                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                {{ session('error') }}
            </div>
            @endif

            @if ($errors->any())
            <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-xl px-4 py-3">
                <p class="font-semibold mb-1">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8 overflow-y-auto min-w-0">
                {{-- Page header slot --}}
                @hasSection('header')
                <div class="mb-8 flex items-start justify-between gap-4 flex-wrap">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-900">@yield('header')</h1>
                        @hasSection('subheader')
                        <p class="text-gray-400 text-sm mt-1">@yield('subheader')</p>
                        @endif
                    </div>
                    @hasSection('header_actions')
                    <div class="flex items-center gap-3">@yield('header_actions')</div>
                    @endif
                </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-100 px-8 py-4 text-xs text-gray-400 flex items-center justify-between">
                <span>© {{ date('Y') }} StayNest. All rights reserved.</span>
                <span>v1.0.0</span>
            </footer>
        </div>

    </div>

    @stack('scripts')
    <script>
        // Mobile sidebar toggle
        document.getElementById('menu-btn')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.toggle('hidden');
        });
    </script>
</body>

</html>