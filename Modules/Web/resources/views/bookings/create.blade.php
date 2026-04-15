{{-- Modules/Web/resources/views/bookings/create.blade.php --}}
@extends('web::components.layouts.web')

@section('title', 'Confirm your booking - StayHub')

@section('content')
<main class="pt-32 pb-32 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-8">
        <div class="flex items-center gap-4 mb-10">
            <a href="{{ route('properties.show', $property->id) }}" class="w-10 h-10 flex items-center justify-center bg-white border border-gray-200 rounded-full hover:bg-gray-100 transition-all">
                <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900">Confirm and pay</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-12">
            {{-- LEFT: BOOKING DETAILS --}}
            <div class="flex-1 space-y-12">
                {{-- TRIP INFO --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
                    <h2 class="text-xl font-bold mb-6">Your trip</h2>
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <p class="font-bold text-gray-900">Dates</p>
                            <p class="text-gray-500">{{ now()->format('M d') }} – {{ now()->addDays(5)->format('M d') }}, {{ now()->year }}</p>
                        </div>
                        <button class="text-sm font-bold underline hover:text-gray-600">Edit</button>
                    </div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-gray-900">Guests</p>
                            <p class="text-gray-500">{{ request('guests', 1) }} guest{{ request('guests', 1) > 1 ? 's' : '' }}</p>
                        </div>
                        <button class="text-sm font-bold underline hover:text-gray-600">Edit</button>
                    </div>
                </div>

                {{-- PAYMENT PLACEHOLDER --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm">
                    <h2 class="text-xl font-bold mb-6">Payment</h2>
                    <div class="flex items-center gap-4 p-4 border border-brand bg-rose-50 rounded-xl mb-6">
                        <div class="w-10 h-10 bg-brand rounded-lg flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-gray-900">Visa ending in 4242</p>
                            <p class="text-xs text-gray-500">Exp 12/26</p>
                        </div>
                        <button class="text-xs font-bold underline">Change</button>
                    </div>

                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->id }}">
                        <input type="hidden" name="check_in_date" value="{{ now()->toDateString() }}">
                        <input type="hidden" name="check_out_date" value="{{ now()->addDays(5)->toDateString() }}">
                        <input type="hidden" name="guests" value="{{ request('guests', 1) }}">

                        <button type="submit" class="w-full bg-brand text-white font-extrabold py-4 rounded-xl hover:bg-brand-dark transition-all shadow-xl active:scale-95 duration-200 text-lg">
                            Confirm and Pay
                        </button>
                    </form>
                    <p class="text-center text-[10px] text-gray-400 mt-4 leading-relaxed">
                        By selecting the button above, you agree to the Guest Re-booking and Refund Policy, and that StayHub can charge your payment method if the Host accepts your request.
                    </p>
                </div>
            </div>

            {{-- RIGHT: SUMMARY CARD --}}
            <div class="w-full lg:w-[420px]">
                <div class="bg-white border border-gray-100 shadow-xl rounded-2xl p-8 sticky top-32">
                    <div class="flex gap-4 mb-8">
                        @php $pImg = $property->primaryImage ? $property->primaryImage->url : 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=400q=80'; @endphp
                        <div class="w-28 h-20 rounded-xl overflow-hidden shrink-0">
                            <img src="{{ $pImg }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 truncate">{{ $property->propertyType->name ?? 'Room' }}</p>
                            <h3 class="text-sm font-bold text-gray-900 truncate mb-1">{{ $property->title }}</h3>
                            <div class="flex items-center gap-1 text-[11px] font-bold">
                                <svg class="w-3 h-3 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                                <span>{{ number_format($property->average_rating ?? 4.8, 1) }}</span>
                                <span class="text-gray-400 font-medium">({{ $property->reviews_count ?? 124 }} reviews)</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-gray-100 mb-8">
                        <h4 class="text-lg font-bold mb-5">Price details</h4>
                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-600">
                                <span>${{ number_format($property->price_per_night, 0) }} x 5 nights</span>
                                <span>${{ number_format($property->price_per_night * 5, 0) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span class="underline">Cleaning fee</span>
                                <span>$40</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span class="underline">Service fee</span>
                                <span>$85</span>
                            </div>
                            <div class="pt-4 border-t border-gray-100 flex justify-between font-extrabold text-gray-900 text-lg">
                                <span>Total (USD)</span>
                                <span>${{ number_format($property->price_per_night * 5 + 125, 0) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- TRUST SYMBOLS --}}
                    <div class="bg-gray-50 rounded-xl p-4 flex gap-4 border border-gray-100">
                        <div class="text-brand">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Secure payment</p>
                            <p class="text-[10px] text-gray-500">Your information is protected by 256-bit SSL encryption.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
