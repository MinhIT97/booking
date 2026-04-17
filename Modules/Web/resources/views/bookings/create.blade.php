@extends('web::components.layouts.web')

@section('title', 'Confirm and Pay — StayNest')

@section('content')
<main class="bg-white min-h-screen pb-20">
    <!-- SIMPLE HEADER -->
    <header class="border-b border-gray-100 bg-white/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('properties.show', $property->slug) }}" class="p-2 hover:bg-gray-100 rounded-full transition-colors group">
                    <svg class="w-5 h-5 text-gray-800 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <h1 class="text-xl font-black text-gray-900 tracking-tight">Confirm and pay</h1>
            </div>
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <svg class="w-7 h-7 text-brand" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                <span class="text-lg font-bold text-gray-900 hidden sm:block">Stay<span class="text-brand">Nest</span></span>
            </a>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            
            {{-- LEFT COLUMN: TRIP DETAILS & PAYMENT --}}
            <div class="lg:col-span-7 space-y-12">
                
                {{-- ERROR DISPLAY --}}
                @if(session('error') || $errors->any())
                    <div class="bg-rose-50 border border-rose-100 p-6 rounded-2xl animate-fadeUp">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <h3 class="text-sm font-black text-rose-800 uppercase tracking-widest mb-1">Trip constraint error</h3>
                                <p class="text-sm text-rose-600 font-medium">
                                    {{ session('error') ?? ($errors->any() ? 'Please check your information and try again.' : '') }}
                                </p>
                                @if($errors->any())
                                    <ul class="mt-2 text-xs text-rose-500 list-disc list-inside font-medium uppercase tracking-tighter">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TRIP SUMMARY --}}
                <section class="animate-fadeUp">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">Your trip</h2>
                    <div class="space-y-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-black uppercase text-gray-400 tracking-widest mb-1">Dates</p>
                                <p class="font-bold text-gray-800">{{ $checkIn ? $checkIn->format('M j') : '---' }} – {{ $checkOut ? $checkOut->format('M j, Y') : '---' }}</p>
                            </div>
                            <a href="{{ route('properties.show', $property->slug) }}" class="text-sm font-black underline hover:text-gray-600 transition-colors">Edit</a>
                        </div>
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-black uppercase text-gray-400 tracking-widest mb-1">Guests</p>
                                <p class="font-bold text-gray-800">{{ $guests }} guest{{ $guests > 1 ? 's' : '' }}</p>
                            </div>
                            <a href="{{ route('properties.show', $property->slug) }}" class="text-sm font-black underline hover:text-gray-600 transition-colors">Edit</a>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- PAYMENT SIMULATION --}}
                <section class="animate-fadeUp" style="animation-delay: 0.1s">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-black text-gray-900">Pay with</h2>
                        <div class="flex items-center gap-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-3 opacity-50">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-5 opacity-50">
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 space-y-4">
                        <div class="relative">
                            <label class="absolute top-3 left-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Card number</label>
                            <input type="text" placeholder="0000 0000 0000 0000" class="w-full bg-white border border-gray-200 pt-7 pb-3 px-4 rounded-xl text-sm font-bold focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all" value="•••• •••• •••• 4242">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative">
                                <label class="absolute top-3 left-4 text-[9px] font-black uppercase tracking-widest text-gray-400">Expiration</label>
                                <input type="text" placeholder="MM/YY" class="w-full bg-white border border-gray-200 pt-7 pb-3 px-4 rounded-xl text-sm font-bold focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all" value="12/26">
                            </div>
                            <div class="relative">
                                <label class="absolute top-3 left-4 text-[9px] font-black uppercase tracking-widest text-gray-400">CVV</label>
                                <input type="text" placeholder="123" class="w-full bg-white border border-gray-200 pt-7 pb-3 px-4 rounded-xl text-sm font-bold focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all" value="***">
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                {{-- POLICY --}}
                <section class="animate-fadeUp" style="animation-delay: 0.2s">
                    <h2 class="text-2xl font-black text-gray-900 mb-6">Required for your trip</h2>
                    <div class="flex gap-6 items-start">
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 mb-1">Message the host</p>
                            <p class="text-sm text-gray-500 leading-relaxed">Let the host know why you're traveling and when you'll check in.</p>
                        </div>
                        <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 bg-gray-100 border border-gray-100">
                             <img src="https://ui-avatars.com/api/?name={{ urlencode($property->host->name ?? 'H') }}&background=000&color=fff&size=256" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <textarea rows="4" class="w-full bg-white border border-gray-200 mt-6 p-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-brand/20 focus:border-brand outline-none transition-all placeholder:text-gray-300" placeholder="Hi {{ explode(' ', $property->host->name ?? 'Host')[0] }}! We're visiting for..."></textarea>
                </section>

                <hr class="border-gray-100">

                <section class="animate-fadeUp" style="animation-delay: 0.3s">
                    <p class="text-[11px] text-gray-400 leading-relaxed uppercase font-black tracking-widest">
                        By selecting the button below, I agree to the <span class="underline text-gray-900">Host's House Rules</span>, <span class="underline text-gray-900">Ground rules for guests</span>, <span class="underline text-gray-900">StayNest Re-booking and Refund Policy</span>, and that I'm responsible for any property damage.
                    </p>
                    
                    <form action="{{ route('bookings.store') }}" method="POST" class="mt-8">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->id }}">
                        <input type="hidden" name="dates" value="{{ $dates }}">
                        <input type="hidden" name="check_in_date" value="{{ $checkIn ? $checkIn->format('Y-m-d') : '' }}">
                        <input type="hidden" name="check_out_date" value="{{ $checkOut ? $checkOut->format('Y-m-d') : '' }}">
                        <input type="hidden" name="guests" value="{{ $guests }}">
                        
                        <button type="submit" class="btn-primary text-white font-extrabold px-12 py-4 rounded-2xl text-lg shadow-xl shadow-brand/20 active:scale-95 transition-all">
                            Confirm and pay
                        </button>
                    </form>
                </section>

            </div>

            {{-- RIGHT COLUMN: STICKY SUMMARY --}}
            <div class="lg:col-span-5 relative">
                <div class="sticky top-28 bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-[0_24px_64px_rgba(0,0,0,0.06)] animate-fadeUp" style="animation-delay: 0.2s">
                    {{-- PROPERTY BRIEF --}}
                    <div class="flex gap-4 pb-8 border-b border-gray-100 mb-8">
                        <div class="w-32 h-24 rounded-2xl overflow-hidden flex-shrink-0">
                            @if($property->primaryImage)
                                <img src="{{ $property->primaryImage->url }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-2xl grayscale">🏠</div>
                            @endif
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ $property->propertyType->name ?? 'Property' }}</p>
                            <h3 class="font-black text-gray-900 line-clamp-2 leading-tight mb-2">{{ $property->title }}</h3>
                            <div class="flex items-center gap-1 text-[10px] font-bold">
                                <svg class="w-3 h-3 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                                <span>{{ number_format($property->average_rating ?? 4.8, 1) }}</span>
                                <span class="text-gray-300">·</span>
                                <span class="text-gray-400">{{ $property->reviews_count ?? 124 }} reviews</span>
                            </div>
                        </div>
                    </div>

                    {{-- PRICE BREAKDOWN --}}
                    <h2 class="text-xl font-black text-gray-900 mb-6">Price details</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center text-sm font-medium text-gray-600">
                            <span class="underline">${{ number_format($property->price_per_night, 0) }} x {{ $nights }} nights</span>
                            <span class="text-gray-900 font-bold">${{ number_format($property->price_per_night * $nights, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-medium text-gray-600">
                            <span class="underline">Cleaning fee</span>
                            <span class="text-gray-900 font-bold">$40</span>
                        </div>
                        <div class="flex justify-between items-center text-sm font-medium text-gray-600">
                            <span class="underline">StayNest service fee</span>
                            <span class="text-gray-900 font-bold">$85</span>
                        </div>
                        
                        <div class="pt-6 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-lg font-black text-gray-900">Total <span class="text-xs text-gray-400 font-bold tracking-tight">(USD)</span></span>
                            <span class="text-2xl font-black text-gray-900 tracking-tighter">${{ number_format($property->price_per_night * $nights + 125, 0) }}</span>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-brand/5 rounded-2xl border border-brand/10 flex gap-4">
                        <svg class="w-5 h-5 text-brand flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[10px] font-bold text-gray-600 leading-relaxed uppercase tracking-wider">
                            This booking is protected by <span class="text-brand font-black">AirCover</span>. Every booking includes protection from Host cancellations, listing inaccuracies, and other issues.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
