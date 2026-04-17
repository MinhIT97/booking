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
                        rose: { 500: '#f43f5e', 600: '#e11d48' },
                        brand: { DEFAULT: '#ff385c', dark: '#d90b2e', light: '#ff6b81' }
                    },
                    keyframes: {
                        fadeUp: { '0%': { opacity: 0, transform: 'translateY(24px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
                        float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-8px)' } }
                    },
                    animation: {
                        fadeUp: 'fadeUp 0.7s ease forwards',
                        'fadeUp-delay': 'fadeUp 0.7s ease 0.2s forwards',
                        'fadeUp-delay2': 'fadeUp 0.7s ease 0.4s forwards',
                        float: 'float 3s ease-in-out infinite'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        [x-cloak] { display: none; }
        .hero-gradient { background: linear-gradient(135deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.2) 60%, transparent 100%); }
        .card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card-hover:hover { transform: translateY(-6px); box-shadow: 0 24px 48px rgba(0,0,0,0.14); }
        .btn-primary { background: linear-gradient(135deg, #ff385c, #d90b2e); transition: all 0.3s ease; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(255,56,92,0.4); }
        .feature-icon { background: linear-gradient(135deg, #fff0f3, #ffe4e8); }
        .nav-link { position: relative; }
        .nav-link::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 0; height: 2px; background: #ff385c; transition: width 0.3s ease; }
        .nav-link:hover::after { width: 100%; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Premium Flatpickr Theming */
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, 
        .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, 
        .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, 
        .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, 
        .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #ff385c !important;
            border-color: #ff385c !important;
        }
        .flatpickr-day.inRange {
            box-shadow: -5px 0 0 #fff0f3, 5px 0 0 #fff0f3 !important;
            background: #fff0f3 !important;
            border-color: #fff0f3 !important;
        }
        .flatpickr-months .flatpickr-month { background: white !important; }
        .flatpickr-current-month .flatpickr-monthDropdown-months:hover { background: rgba(0,0,0,0.05) !important; }
        .flatpickr-weekdays { background: white !important; }
        span.flatpickr-weekday { color: #9ca3af !important; font-weight: 700 !important; }
    </style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-white text-gray-900 overflow-x-hidden">

    {{-- GLOBAL NOTIFICATIONS --}}
    @if(session('success') || session('error'))
        <div class="fixed top-24 right-8 z-[60] animate-fadeUp">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 flex items-center gap-4 min-w-[320px]">
                @if(session('success'))
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                @endif
                <div>
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ session('success') ? 'Success!' : 'Notice' }}</h4>
                    <p class="text-xs text-gray-500 font-medium">{{ session('success') ?? session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr(".datepicker-range", {
                mode: "range",
                dateFormat: "d M, Y",
                minDate: "today",
                showMonths: 2,
                altInput: true,
                altFormat: "d M, Y",
                onReady: function(selectedDates, dateStr, instance) {
                    instance.altInput.classList.add('w-full', 'text-gray-900', 'text-sm', 'font-bold', 'focus:outline-none', 'bg-transparent', 'placeholder-gray-400');
                    if (instance.input.classList.contains('mini-picker')) {
                        instance.altInput.classList.remove('text-sm');
                        instance.altInput.classList.add('text-xs', 'w-40');
                    }
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
