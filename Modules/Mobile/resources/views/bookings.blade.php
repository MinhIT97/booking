@extends('mobile::components.layouts.mobile')

@section('title', 'Trips')

@section('header')
    <h1 class="text-3xl font-bold px-2">Trips</h1>
@endsection

@section('content')
    <div class="px-6 py-4">
        {{-- Tabs --}}
        <div class="flex border-b border-gray-100 mb-8 overflow-x-auto hide-scrollbar">
            <button class="px-4 py-3 text-[14px] font-bold border-b-2 border-black -mb-[1px]">Upcoming</button>
            <button class="px-4 py-3 text-[14px] font-medium text-gray-500">Past</button>
            <button class="px-4 py-3 text-[14px] font-medium text-gray-500">Cancelled</button>
        </div>

        {{-- Bookings List --}}
        <div class="space-y-8">
            @forelse($bookings as $booking)
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="relative h-48 w-full">
                        <img src="{{ $booking->property->primaryImage->url ?? 'https://ui-avatars.com/api/?name=Trip' }}" class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[12px] font-bold shadow-sm">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-[17px] font-bold">{{ $booking->property->city }}</h3>
                                <p class="text-[14px] text-gray-500 truncate max-w-[200px]">{{ $booking->property->title }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-gray-50 flex flex-col items-center justify-center border border-gray-100">
                                <span class="text-[10px] font-bold uppercase text-gray-400 leading-none mb-1">{{ $booking->check_in_date->format('M') }}</span>
                                <span class="text-[16px] font-bold text-gray-900 leading-none">{{ $booking->check_in_date->format('d') }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-[13px] text-gray-500 font-medium pt-3 border-t border-gray-50 mt-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>{{ $booking->check_in_date->format('M d') }} - {{ $booking->check_out_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl">🧳</div>
                    <h3 class="text-xl font-bold mb-2">No trips booked...yet!</h3>
                    <p class="text-gray-500 text-[15px] px-8 mb-8 leading-relaxed">Time to dust off your bags and start planning your next adventure.</p>
                    <a href="{{ route('mobile.home') }}" class="inline-block border border-black px-6 py-3 rounded-xl font-bold active:bg-gray-50 transition-colors">Start searching</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
