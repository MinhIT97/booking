<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>StayNest — Find Your Perfect Stay</title>
    <meta name="description" content="Book unique homes, villas, and apartments worldwide. Easy, secure, and fast." />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        rose: {
                            500: '#f43f5e',
                            600: '#e11d48'
                        },
                        brand: {
                            DEFAULT: '#ff385c',
                            dark: '#d90b2e',
                            light: '#ff6b81'
                        }
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': {
                                opacity: 0,
                                transform: 'translateY(24px)'
                            },
                            '100%': {
                                opacity: 1,
                                transform: 'translateY(0)'
                            }
                        },
                        float: {
                            '0%,100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-8px)'
                            }
                        }
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
    <style>
        [x-cloak] {
            display: none;
        }

        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.55) 0%, rgba(0, 0, 0, 0.2) 60%, transparent 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.14);
        }

        .btn-primary {
            background: linear-gradient(135deg, #ff385c, #d90b2e);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(255, 56, 92, 0.4);
        }

        .feature-icon {
            background: linear-gradient(135deg, #fff0f3, #ffe4e8);
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #ff385c;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        html { scroll-behavior: smooth; }

        /* Mobile menu */
        #mobile-menu { transform: translateX(-100%); transition: transform 0.35s cubic-bezier(0.4,0,0.2,1); }
        #mobile-menu.open { transform: translateX(0); }
        #mobile-overlay { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        #mobile-overlay.open { opacity: 1; pointer-events: auto; }

        /* Detail slide-over */
        #detail-panel { transform: translateX(100%); transition: transform 0.4s cubic-bezier(0.4,0,0.2,1); }
        #detail-panel.open { transform: translateX(0); }
        #detail-overlay { opacity: 0; pointer-events: none; transition: opacity 0.3s ease; }
        #detail-overlay.open { opacity: 1; pointer-events: auto; }

        /* Booking modal */
        #booking-modal { opacity: 0; pointer-events: none; transition: opacity 0.25s ease; }
        #booking-modal.open { opacity: 1; pointer-events: auto; }
        #booking-modal .modal-box { transform: scale(0.95) translateY(16px); transition: transform 0.3s ease; }
        #booking-modal.open .modal-box { transform: scale(1) translateY(0); }

        /* Filter chip active */
        .filter-chip.active { background: #ff385c; color: #fff; border-color: #ff385c; }

        /* Price range */
        input[type=range]::-webkit-slider-thumb { background: #ff385c; }
        input[type=range] { accent-color: #ff385c; }
    </style>
</head>

<body class="font-sans antialiased bg-white text-gray-900">

    <!-- NAV -->
    <nav class="fixed top-0 inset-x-0 z-50 bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <a href="#" class="flex items-center gap-2">
                <svg class="w-8 h-8 text-brand" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                </svg>
                <span class="text-xl font-800 font-bold text-gray-900">Stay<span class="text-brand">Nest</span></span>
            </a>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="#features" class="nav-link hover:text-brand transition-colors">Features</a>
                <a href="#properties" class="nav-link hover:text-brand transition-colors">Properties</a>
                <a href="#cta" class="nav-link hover:text-brand transition-colors">Host</a>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" class="hidden sm:block text-sm font-medium text-gray-700 hover:text-brand transition-colors">Sign in</a>
                <a href="#" class="btn-primary text-white text-sm font-semibold px-5 py-2.5 rounded-full">Get Started</a>
                <!-- Hamburger -->
                <button id="hamburger-btn" class="md:hidden flex flex-col gap-1.5 p-2 rounded-lg hover:bg-gray-100 transition" aria-label="Open menu">
                    <span class="block w-5 h-0.5 bg-gray-700 transition-all"></span>
                    <span class="block w-5 h-0.5 bg-gray-700 transition-all"></span>
                    <span class="block w-5 h-0.5 bg-gray-700 transition-all"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="/hero_villa.png" alt="Luxury Villa" class="w-full h-full object-cover object-center" />
            <div class="hero-gradient absolute inset-0"></div>
        </div>
        <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
            <div class="opacity-0 animate-fadeUp">
                <span class="inline-block bg-white/20 backdrop-blur-sm text-white text-sm font-medium px-4 py-1.5 rounded-full mb-6 border border-white/30">
                    🌍 2,000+ properties worldwide
                </span>
            </div>
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white leading-tight mb-6 opacity-0 animate-fadeUp" style="animation-delay:0.1s">
                Find your perfect<br><span class="text-rose-400">getaway</span>
            </h1>
            <p class="text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-10 opacity-0 animate-fadeUp" style="animation-delay:0.2s">
                Discover handpicked homes, villas, and private stays. Book in minutes, enjoy forever.
            </p>

            <!-- SEARCH BAR -->
            <div class="opacity-0 animate-fadeUp bg-white rounded-2xl shadow-2xl p-2 flex flex-col sm:flex-row gap-2 max-w-3xl mx-auto" style="animation-delay:0.3s">
                <div class="flex items-center gap-3 flex-1 px-4 py-3 border border-gray-200 rounded-xl">
                    <svg class="w-5 h-5 text-brand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <input id="search-location" type="text" placeholder="Where are you going?" class="w-full text-gray-700 text-sm focus:outline-none bg-transparent placeholder-gray-400" />
                </div>
                <div class="flex items-center gap-3 flex-1 px-4 py-3 border border-gray-200 rounded-xl">
                    <svg class="w-5 h-5 text-brand flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <input id="search-date" type="text" placeholder="Check-in → Check-out" class="w-full text-gray-700 text-sm focus:outline-none bg-transparent placeholder-gray-400" />
                </div>
                <button id="search-btn" class="btn-primary text-white font-semibold px-8 py-3 rounded-xl flex items-center gap-2 justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
            </div>

            <div class="mt-8 flex items-center justify-center gap-6 text-white/70 text-sm opacity-0 animate-fadeUp" style="animation-delay:0.4s">
                <span>✨ No booking fees</span>
                <span>•</span>
                <span>🔒 Secure payments</span>
                <span>•</span>
                <span>⭐ Verified hosts</span>
            </div>
        </div>

        <!-- Scroll hint -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-float">
            <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </section>

    <!-- FEATURES -->
    <section id="features" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-4">Why choose <span class="text-brand">StayNest</span>?</h2>
                <p class="text-gray-500 text-lg max-w-xl mx-auto">Everything you need for the perfect stay, built into one seamless experience.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow duration-300 text-center group">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Easy Booking</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Book your dream stay in under 2 minutes with our streamlined process.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow duration-300 text-center group">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Verified Rooms</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Every property is manually verified by our team for quality and accuracy.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow duration-300 text-center group">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Secure Payment</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Bank-grade encryption protects every transaction. Pay with confidence.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-lg transition-shadow duration-300 text-center group">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">24/7 Support</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Our team is always here to help, day or night, wherever you are.</p>
                </div>

            </div>
        </div>
    </section>

    <!-- PROPERTIES -->
    <section id="properties" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-3">Featured <span class="text-brand">Stays</span></h2>
                    <p class="text-gray-500">Hand-picked properties loved by our guests.</p>
                </div>
                <a href="#" class="hidden sm:flex items-center gap-1.5 text-brand font-semibold text-sm hover:gap-3 transition-all">
                    View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group">
                    <div class="relative overflow-hidden h-56">
                        <img src="/property_apartment.png" alt="City Apartment" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-semibold px-3 py-1 rounded-full">Entire apartment</span>
                        <button class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow text-gray-400 hover:text-brand transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Modern Studio, City View</h3>
                                <p class="text-gray-500 text-sm flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    New York, USA
                                </p>
                            </div>
                            <div class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                4.92
                            </div>
                        </div>
                        <div class="border-t border-gray-100 pt-3 mt-3 flex items-center justify-between">
                            <div><span class="text-xl font-bold text-gray-900">$120</span><span class="text-gray-400 text-sm"> /night</span></div>
                            <button class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-lg">Book Now</button>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group">
                    <div class="relative overflow-hidden h-56">
                        <img src="/property_beach.png" alt="Bali Beach Villa" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-semibold px-3 py-1 rounded-full">Private villa</span>
                        <button class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow text-brand">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        <div class="absolute bottom-3 left-4">
                            <span class="bg-brand text-white text-xs font-bold px-2.5 py-1 rounded-full">🔥 Top Pick</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Oceanfront Bali Villa</h3>
                                <p class="text-gray-500 text-sm flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    Seminyak, Bali
                                </p>
                            </div>
                            <div class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                5.0
                            </div>
                        </div>
                        <div class="border-t border-gray-100 pt-3 mt-3 flex items-center justify-between">
                            <div><span class="text-xl font-bold text-gray-900">$380</span><span class="text-gray-400 text-sm"> /night</span></div>
                            <button class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-lg">Book Now</button>
                        </div>
                    </div>
                </div>

                <div class="card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group">
                    <div class="relative overflow-hidden h-56">
                        <img src="/property_cabin.png" alt="Mountain Cabin" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-900 text-xs font-semibold px-3 py-1 rounded-full">Entire cabin</span>
                        <button class="absolute top-4 right-4 w-8 h-8 bg-white rounded-full flex items-center justify-center shadow text-gray-400 hover:text-brand transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-bold text-gray-900">Alpine Winter Cabin</h3>
                                <p class="text-gray-500 text-sm flex items-center gap-1 mt-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    Innsbruck, Austria
                                </p>
                            </div>
                            <div class="flex items-center gap-1 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                4.87
                            </div>
                        </div>
                        <div class="border-t border-gray-100 pt-3 mt-3 flex items-center justify-between">
                            <div><span class="text-xl font-bold text-gray-900">$210</span><span class="text-gray-400 text-sm"> /night</span></div>
                            <button class="btn-primary text-white text-xs font-semibold px-4 py-2 rounded-lg">Book Now</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- STATS -->
    <section class="py-16 bg-rose-50">
        <div class="max-w-5xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <p class="text-4xl font-extrabold text-brand">2M+</p>
                <p class="text-gray-500 mt-1 text-sm">Happy guests</p>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-brand">50K+</p>
                <p class="text-gray-500 mt-1 text-sm">Properties</p>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-brand">120+</p>
                <p class="text-gray-500 mt-1 text-sm">Countries</p>
            </div>
            <div>
                <p class="text-4xl font-extrabold text-brand">4.9★</p>
                <p class="text-gray-500 mt-1 text-sm">Avg. rating</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="cta" class="py-28 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <span class="inline-block bg-rose-100 text-brand text-sm font-semibold px-4 py-1.5 rounded-full mb-6">Ready to explore?</span>
            <h2 class="text-5xl font-extrabold text-gray-900 mb-6 leading-tight">Start your next adventure<br><span class="text-brand">today</span></h2>
            <p class="text-gray-500 text-lg max-w-lg mx-auto mb-10">Join millions of travellers who found their perfect stay with StayNest. No fees. No surprises.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a id="cta-book-btn" href="#" class="btn-primary text-white font-bold text-lg px-10 py-4 rounded-2xl inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Start Booking Now
                </a>
                <a id="cta-host-btn" href="#" class="border-2 border-gray-200 text-gray-700 hover:border-brand hover:text-brand font-bold text-lg px-10 py-4 rounded-2xl transition-colors inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Become a Host
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-7 h-7 text-brand" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z" />
                        </svg>
                        <span class="text-white font-bold text-xl">Stay<span class="text-brand">Nest</span></span>
                    </div>
                    <p class="text-sm leading-relaxed">Discover the world one stay at a time. Book unique homes with verified hosts.</p>
                    <div class="flex gap-4 mt-5">
                        <a id="footer-twitter" href="#" class="w-9 h-9 bg-gray-800 hover:bg-brand rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                            </svg>
                        </a>
                        <a id="footer-instagram" href="#" class="w-9 h-9 bg-gray-800 hover:bg-brand rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01" />
                            </svg>
                        </a>
                        <a id="footer-facebook" href="#" class="w-9 h-9 bg-gray-800 hover:bg-brand rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Explore</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-brand transition-colors">Beach Houses</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Mountain Cabins</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">City Apartments</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Luxury Villas</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Company</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-brand transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Press</a></li>
                        <li><a href="#" class="hover:text-brand transition-colors">Blog</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>hello@staynest.com</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>+1 (800) STAY-NOW</li>
                        <li class="flex items-center gap-2"><svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>San Francisco, CA</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm">
                <p>&copy; 2026 StayNest. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-brand transition-colors">Privacy</a>
                    <a href="#" class="hover:text-brand transition-colors">Terms</a>
                    <a href="#" class="hover:text-brand transition-colors">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

<!-- ============================================================ -->
<!-- MOBILE MENU OVERLAY -->
<div id="mobile-overlay" class="fixed inset-0 bg-black/40 z-40"></div>
<!-- MOBILE MENU DRAWER -->
<aside id="mobile-menu" class="fixed top-0 left-0 h-full w-72 bg-white z-50 shadow-2xl flex flex-col">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <span class="text-xl font-bold text-gray-900">Stay<span class="text-brand">Nest</span></span>
        <button id="mobile-close" class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <nav class="flex flex-col gap-1 px-4 py-6 text-gray-700 text-sm font-medium">
        <a href="#features" class="px-4 py-3 rounded-xl hover:bg-rose-50 hover:text-brand transition-colors" onclick="closeMobileMenu()">Features</a>
        <a href="#properties" class="px-4 py-3 rounded-xl hover:bg-rose-50 hover:text-brand transition-colors" onclick="closeMobileMenu()">Properties</a>
        <a href="#listings" class="px-4 py-3 rounded-xl hover:bg-rose-50 hover:text-brand transition-colors" onclick="closeMobileMenu()">All Listings</a>
        <a href="#cta" class="px-4 py-3 rounded-xl hover:bg-rose-50 hover:text-brand transition-colors" onclick="closeMobileMenu()">Become a Host</a>
    </nav>
    <div class="mt-auto px-6 py-6 border-t border-gray-100 flex flex-col gap-3">
        <a href="#" class="text-center py-3 rounded-xl border-2 border-gray-200 text-sm font-semibold text-gray-700 hover:border-brand hover:text-brand transition-colors">Sign In</a>
        <a href="#" class="btn-primary text-white text-center py-3 rounded-xl text-sm font-semibold">Get Started</a>
    </div>
</aside>

<!-- ============================================================ -->
<!-- FULL LISTINGS SECTION -->
<section id="listings" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-1">All <span class="text-brand">Listings</span></h2>
            <p class="text-gray-500 text-sm">Explore our full collection of verified properties.</p>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row gap-4 mb-8">
            <div class="flex flex-wrap gap-2">
                <button class="filter-chip active border border-gray-200 text-sm font-medium px-4 py-2 rounded-full bg-white hover:border-brand transition-colors" onclick="setFilter(this,'all')">All</button>
                <button class="filter-chip border border-gray-200 text-sm font-medium px-4 py-2 rounded-full bg-white hover:border-brand transition-colors" onclick="setFilter(this,'villa')">🏖 Villas</button>
                <button class="filter-chip border border-gray-200 text-sm font-medium px-4 py-2 rounded-full bg-white hover:border-brand transition-colors" onclick="setFilter(this,'apartment')">🏙 Apartments</button>
                <button class="filter-chip border border-gray-200 text-sm font-medium px-4 py-2 rounded-full bg-white hover:border-brand transition-colors" onclick="setFilter(this,'cabin')">🏔 Cabins</button>
            </div>
            <div class="flex items-center gap-3 ml-auto">
                <label class="text-xs text-gray-500 font-medium whitespace-nowrap">Max price: <span id="price-label">$500</span></label>
                <input id="price-range" type="range" min="50" max="1000" value="500" step="10" class="w-32" oninput="document.getElementById('price-label').textContent='$'+this.value" />
            </div>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="listings-grid">

            <div class="listing-card card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group" data-type="apartment" data-price="120" data-title="Modern Studio, City View" data-loc="New York, USA" data-rating="4.92" data-nights="2" data-img="/property_apartment.png" data-desc="A sleek city-centre studio with floor-to-ceiling windows overlooking the Manhattan skyline. Fully equipped kitchen, high-speed WiFi, smart TV on-site gym access included." onclick="openDetail(this)">
                <div class="relative overflow-hidden h-48">
                    <img src="/property_apartment.png" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <span class="absolute top-3 left-3 bg-white/90 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full">Apartment</span>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-sm">Modern Studio, City View</h3>
                    <p class="text-gray-400 text-xs mt-0.5">📍 New York, USA</p>
                    <div class="flex items-center justify-between mt-3">
                        <div><span class="font-bold text-gray-900">$120</span><span class="text-gray-400 text-xs"> /night</span></div>
                        <span class="text-xs text-amber-500 font-semibold">★ 4.92</span>
                    </div>
                </div>
            </div>

            <div class="listing-card card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group" data-type="villa" data-price="380" data-title="Oceanfront Bali Villa" data-loc="Seminyak, Bali" data-rating="5.0" data-nights="3" data-img="/property_beach.png" data-desc="A stunning Balinese villa perched above the Indian Ocean. Private infinity pool, open-air living pavilion, personal butler service, and direct beach access." onclick="openDetail(this)">
                <div class="relative overflow-hidden h-48">
                    <img src="/property_beach.png" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <span class="absolute top-3 left-3 bg-white/90 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full">Villa</span>
                    <span class="absolute top-3 right-3 bg-brand text-white text-xs font-bold px-2.5 py-1 rounded-full">🔥 Top</span>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-sm">Oceanfront Bali Villa</h3>
                    <p class="text-gray-400 text-xs mt-0.5">📍 Seminyak, Bali</p>
                    <div class="flex items-center justify-between mt-3">
                        <div><span class="font-bold text-gray-900">$380</span><span class="text-gray-400 text-xs"> /night</span></div>
                        <span class="text-xs text-amber-500 font-semibold">★ 5.0</span>
                    </div>
                </div>
            </div>

            <div class="listing-card card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group" data-type="cabin" data-price="210" data-title="Alpine Winter Cabin" data-loc="Innsbruck, Austria" data-rating="4.87" data-nights="4" data-img="/property_cabin.png" data-desc="A cosy Scandinavian cabin nestled in the Austrian Alps with breathtaking mountain panoramas, a roaring fireplace, and ski-in/ski-out access." onclick="openDetail(this)">
                <div class="relative overflow-hidden h-48">
                    <img src="/property_cabin.png" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <span class="absolute top-3 left-3 bg-white/90 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full">Cabin</span>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-sm">Alpine Winter Cabin</h3>
                    <p class="text-gray-400 text-xs mt-0.5">📍 Innsbruck, Austria</p>
                    <div class="flex items-center justify-between mt-3">
                        <div><span class="font-bold text-gray-900">$210</span><span class="text-gray-400 text-xs"> /night</span></div>
                        <span class="text-xs text-amber-500 font-semibold">★ 4.87</span>
                    </div>
                </div>
            </div>

            <div class="listing-card card-hover bg-white rounded-2xl overflow-hidden shadow-md cursor-pointer group" data-type="villa" data-price="290" data-title="Tropical Garden Villa" data-loc="Ubud, Bali" data-rating="4.96" data-nights="2" data-img="/hero_villa.png" data-desc="Hidden among tropical rice paddies and lush jungle, this architect-designed villa features a private pool, outdoor rain shower, and daily private chef breakfast." onclick="openDetail(this)">
                <div class="relative overflow-hidden h-48">
                    <img src="/hero_villa.png" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <span class="absolute top-3 left-3 bg-white/90 text-gray-800 text-xs font-semibold px-2.5 py-1 rounded-full">Villa</span>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 text-sm">Tropical Garden Villa</h3>
                    <p class="text-gray-400 text-xs mt-0.5">📍 Ubud, Bali</p>
                    <div class="flex items-center justify-between mt-3">
                        <div><span class="font-bold text-gray-900">$290</span><span class="text-gray-400 text-xs"> /night</span></div>
                        <span class="text-xs text-amber-500 font-semibold">★ 4.96</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- DETAIL SLIDE-OVER OVERLAY -->
<div id="detail-overlay" class="fixed inset-0 bg-black/50 z-40" onclick="closeDetail()"></div>
<!-- DETAIL PANEL -->
<aside id="detail-panel" class="fixed top-0 right-0 h-full w-full sm:w-[480px] bg-white z-50 overflow-y-auto shadow-2xl flex flex-col">
    <div class="relative">
        <img id="dp-img" src="" class="w-full h-64 object-cover" />
        <button onclick="closeDetail()" class="absolute top-4 left-4 w-9 h-9 bg-white rounded-full flex items-center justify-center shadow text-gray-600 hover:text-brand">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="absolute bottom-4 left-4"><span id="dp-rating" class="bg-white/90 backdrop-blur text-amber-500 text-sm font-bold px-3 py-1 rounded-full"></span></div>
    </div>
    <div class="p-6 flex-1 flex flex-col">
        <h2 id="dp-title" class="text-2xl font-extrabold text-gray-900 mb-1"></h2>
        <p id="dp-loc" class="text-gray-400 text-sm mb-4"></p>
        <div class="flex gap-4 mb-6">
            <div class="flex-1 bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-400 mb-1">Per night</p>
                <p id="dp-price" class="text-xl font-extrabold text-brand"></p>
            </div>
            <div class="flex-1 bg-gray-50 rounded-xl p-3 text-center">
                <p class="text-xs text-gray-400 mb-1">Min nights</p>
                <p id="dp-nights" class="text-xl font-extrabold text-gray-900"></p>
            </div>
        </div>
        <h3 class="font-bold text-gray-900 mb-2">About this place</h3>
        <p id="dp-desc" class="text-gray-500 text-sm leading-relaxed mb-6"></p>
        <div class="grid grid-cols-3 gap-3 mb-8">
            <div class="bg-rose-50 rounded-xl p-3 text-center text-xs text-gray-600">🏊 Pool</div>
            <div class="bg-rose-50 rounded-xl p-3 text-center text-xs text-gray-600">📶 WiFi</div>
            <div class="bg-rose-50 rounded-xl p-3 text-center text-xs text-gray-600">🅿️ Parking</div>
            <div class="bg-rose-50 rounded-xl p-3 text-center text-xs text-gray-600">🍳 Kitchen</div>
            <div class="bg-rose-50 rounded-xl p-3 text-center text-xs text-gray-600">❄️ A/C</div>
            <div class="bg-rose-50 rounded-xl p-3 text-center text-xs text-gray-600">🐾 Pets OK</div>
        </div>
        <button onclick="openBooking()" class="btn-primary text-white font-bold py-4 rounded-2xl text-center mt-auto w-full">Reserve Now →</button>
    </div>
</aside>

<!-- ============================================================ -->
<!-- BOOKING MODAL -->
<div id="booking-modal" class="fixed inset-0 z-[60] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60" onclick="closeBooking()"></div>
    <div class="modal-box relative bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
        <button onclick="closeBooking()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <h3 class="text-xl font-extrabold text-gray-900 mb-1">Complete Your Booking</h3>
        <p id="modal-title" class="text-sm text-gray-400 mb-6"></p>
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Check-in Date</label>
                <input id="modal-checkin" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Check-out Date</label>
                <input id="modal-checkout" type="date" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand" />
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Guests</label>
                <select class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    <option>1 guest</option>
                    <option>2 guests</option>
                    <option>3 guests</option>
                    <option>4+ guests</option>
                </select>
            </div>
        </div>
        <div class="mt-6 bg-gray-50 rounded-2xl p-4 text-sm">
            <div class="flex justify-between text-gray-500 mb-1">
                <span id="modal-rate"></span><span id="modal-subtotal"></span>
            </div>
            <div class="flex justify-between font-bold text-gray-900 text-base mt-2 pt-2 border-t border-gray-200">
                <span>Total</span><span id="modal-total"></span>
            </div>
        </div>
        <button class="btn-primary w-full text-white font-bold py-4 rounded-2xl mt-5 text-base">Confirm &amp; Book →</button>
    </div>
</div>

<!-- ============================================================ -->
<script>
    // ---- Mobile Menu ----
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileOverlay = document.getElementById('mobile-overlay');
    document.getElementById('hamburger-btn').addEventListener('click', () => {
        mobileMenu.classList.add('open');
        mobileOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    });
    document.getElementById('mobile-close').addEventListener('click', closeMobileMenu);
    mobileOverlay.addEventListener('click', closeMobileMenu);
    function closeMobileMenu() {
        mobileMenu.classList.remove('open');
        mobileOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    // ---- Filter Chips ----
    function setFilter(btn, type) {
        document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        const maxPrice = parseInt(document.getElementById('price-range').value);
        document.querySelectorAll('.listing-card').forEach(card => {
            const match = (type === 'all' || card.dataset.type === type) && parseInt(card.dataset.price) <= maxPrice;
            card.style.display = match ? '' : 'none';
        });
    }
    document.getElementById('price-range').addEventListener('input', function() {
        const active = document.querySelector('.filter-chip.active');
        const type = active ? active.getAttribute('onclick').match(/'([^']+)'/)?.[1] || 'all' : 'all';
        const max = parseInt(this.value);
        document.querySelectorAll('.listing-card').forEach(card => {
            const match = (type === 'all' || card.dataset.type === type) && parseInt(card.dataset.price) <= max;
            card.style.display = match ? '' : 'none';
        });
    });

    // ---- Detail Panel ----
    let currentCard = null;
    function openDetail(card) {
        currentCard = card;
        document.getElementById('dp-img').src = card.dataset.img;
        document.getElementById('dp-title').textContent = card.dataset.title;
        document.getElementById('dp-loc').textContent = '📍 ' + card.dataset.loc;
        document.getElementById('dp-rating').textContent = '★ ' + card.dataset.rating;
        document.getElementById('dp-price').textContent = '$' + card.dataset.price;
        document.getElementById('dp-nights').textContent = card.dataset.nights;
        document.getElementById('dp-desc').textContent = card.dataset.desc;
        document.getElementById('detail-panel').classList.add('open');
        document.getElementById('detail-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeDetail() {
        document.getElementById('detail-panel').classList.remove('open');
        document.getElementById('detail-overlay').classList.remove('open');
        document.body.style.overflow = '';
    }

    // ---- Booking Modal ----
    function openBooking() {
        if (!currentCard) return;
        const price = parseInt(currentCard.dataset.price);
        const nights = parseInt(currentCard.dataset.nights);
        document.getElementById('modal-title').textContent = currentCard.dataset.title;
        document.getElementById('modal-rate').textContent = '$' + price + ' x ' + nights + ' nights';
        document.getElementById('modal-subtotal').textContent = '$' + (price * nights);
        document.getElementById('modal-total').textContent = '$' + (price * nights);
        document.getElementById('booking-modal').classList.add('open');
    }
    function closeBooking() {
        document.getElementById('booking-modal').classList.remove('open');
    }
    // Also wire existing "Book Now" buttons in the featured cards
    document.querySelectorAll('#properties .btn-primary, .card-hover .btn-primary').forEach((btn, i) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const prices = [120, 380, 210];
            const titles = ['Modern Studio, City View', 'Oceanfront Bali Villa', 'Alpine Winter Cabin'];
            const nts = [2, 3, 4];
            document.getElementById('modal-title').textContent = titles[i] || titles[0];
            document.getElementById('modal-rate').textContent = '$' + (prices[i]||120) + ' x ' + (nts[i]||2) + ' nights';
            document.getElementById('modal-subtotal').textContent = '$' + ((prices[i]||120)*(nts[i]||2));
            document.getElementById('modal-total').textContent = '$' + ((prices[i]||120)*(nts[i]||2));
            document.getElementById('booking-modal').classList.add('open');
        });
    });
</script>

</body>

</html>