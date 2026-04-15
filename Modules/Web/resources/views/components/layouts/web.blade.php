<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'StayNest — Find Your Perfect Stay')</title>
    <meta name="description" content="@yield('meta_description', 'Book unique homes, villas, and apartments worldwide.')" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            DEFAULT: '#ff385c',
                            dark: '#d90b2e',
                            light: '#ff6b81'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.2) 60%, transparent 100%);
        }
        .btn-brand {
            background: linear-gradient(135deg, #ff385c, #d90b2e);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 56, 92, 0.4);
        }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-900 overflow-x-hidden">

    @yield('content')

    @stack('scripts')
</body>
</html>
