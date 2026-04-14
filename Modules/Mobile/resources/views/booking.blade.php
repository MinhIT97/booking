@extends('mobile::components.layouts.mobile')

@section('title', 'Confirm and Pay')

@section('header')
    <a href="{{ route('mobile.detail', $property->id) }}" class="text-black p-2 -ml-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="text-[17px] font-bold">Confirm and pay</h1>
    <div class="w-8"></div> {{-- Spacer --}}
@endsection

@section('content')
    <div class="px-6 py-8">
        {{-- Selected Property Card --}}
        <div class="flex gap-4 p-4 bg-gray-50 rounded-2xl mb-8">
            <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0">
                <img src="{{ $property->primaryImage->url ?? 'https://ui-avatars.com/api/?name=Room' }}" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col justify-center">
                <p class="text-[11px] text-gray-500 font-bold uppercase tracking-wider mb-1">Stay with {{ $property->host->name ?? 'Host' }}</p>
                <h2 class="text-[15px] font-bold line-clamp-2 leading-tight">{{ $property->title }}</h2>
                <div class="mt-2 flex items-center gap-1 text-[12px] font-bold">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <span>{{ $property->average_rating ?? 4.8 }}</span>
                    <span class="text-gray-400">·</span>
                    <span>Rare find</span>
                </div>
            </div>
        </div>

        {{-- Booking Details --}}
        <div class="space-y-8">
            <h3 class="text-xl font-bold">Your trip</h3>
            
            <div class="flex justify-between items-center group active:bg-gray-50 -mx-2 px-2 py-1 rounded-xl transition-colors">
                <div>
                    <p class="text-[15px] font-bold">Dates</p>
                    <p class="text-gray-500 text-[14px]">Dec 15 - 20</p>
                </div>
                <button class="text-black font-bold underline text-[14px]">Edit</button>
            </div>

            <div class="flex justify-between items-center group active:bg-gray-50 -mx-2 px-2 py-1 rounded-xl transition-colors">
                <div>
                    <p class="text-[15px] font-bold">Guests</p>
                    <p class="text-gray-500 text-[14px]">2 guests</p>
                </div>
                <button class="text-black font-bold underline text-[14px]">Edit</button>
            </div>
        </div>

        <div class="my-8 h-[1px] bg-gray-100"></div>

        {{-- Price Details --}}
        <div class="space-y-4">
            <h3 class="text-xl font-bold mb-4">Price details</h3>
            <div class="flex justify-between text-[15px]">
                <span class="text-gray-600 underline text-sm">${{ number_format($property->price_per_night, 0) }} x 5 nights</span>
                <span class="text-gray-900">${{ number_format($property->price_per_night * 5, 0) }}</span>
            </div>
            <div class="flex justify-between text-[15px]">
                <span class="text-gray-600 underline text-sm">StayNest service fee</span>
                <span class="text-gray-900">$84.00</span>
            </div>
            <div class="flex justify-between text-[15px]">
                <span class="text-gray-600 underline text-sm">Taxes</span>
                <span class="text-gray-900">$12.50</span>
            </div>
            <div class="pt-4 border-t border-gray-50 flex justify-between">
                <span class="font-bold text-[17px]">Total (USD)</span>
                <span class="font-bold text-[17px]">${{ number_format($property->price_per_night * 5 + 84 + 12.5, 2) }}</span>
            </div>
        </div>

        <div class="my-8 h-[1px] bg-gray-100"></div>

        {{-- Payment Message --}}
        <div class="mb-10">
            <p class="text-[11px] text-gray-500 leading-relaxed">By selecting the button below, I agree to the <a href="#" class="underline font-bold text-black">Guest Revisions Policy</a> and <a href="#" class="underline font-bold text-black">House Rules</a>. I also agree to pay the total amount shown, which includes Service Fees and Taxes.</p>
        </div>

        {{-- Final Confirm Button --}}
        <form action="{{ route('mobile.booking.store') }}" method="POST">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id }}">
            <input type="hidden" name="check_in_date" value="2024-12-15">
            <input type="hidden" name="check_out_date" value="2024-12-20">
            <button type="submit" class="w-full bg-brand text-white py-4 rounded-xl font-bold text-[17px] active:scale-[0.98] transition-all shadow-xl shadow-brand/20">
                Confirm and pay
            </button>
        </form>
    </div>
@endsection
