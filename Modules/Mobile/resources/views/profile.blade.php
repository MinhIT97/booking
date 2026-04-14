@extends('mobile::components.layouts.mobile')

@section('title', 'Profile')

@section('header')
    <h1 class="text-3xl font-bold px-2">Profile</h1>
@endsection

@section('content')
    <div class="px-6 py-8">
        {{-- User Hero --}}
        <div class="flex items-center gap-5 mb-10 pb-10 border-b border-gray-100">
            <div class="w-20 h-20 rounded-full border border-gray-200 overflow-hidden shadow-sm">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Guest') }}&size=128&background=random" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-2xl font-bold">{{ auth()->user()->name ?? 'Guest User' }}</h2>
                <p class="text-gray-500 text-[15px]">{{ auth()->user()->email ?? 'Sign in to see details' }}</p>
                @auth
                    <a href="{{ route('mobile.profile') }}" class="text-[13px] font-bold underline mt-1 inline-block">Show profile</a>
                @endauth
            </div>
        </div>

        {{-- Menu Sections --}}
        <div class="space-y-8">
            <div>
                <h3 class="text-[20px] font-bold mb-4">Settings</h3>
                <div class="space-y-1">
                    @foreach(['Personal info', 'Payments and payouts', 'Taxes', 'Login & security'] as $item)
                        <button class="w-full h-14 flex items-center justify-between group active:bg-gray-50 -mx-2 px-2 rounded-xl transition-colors">
                            <span class="text-[15px] font-medium text-gray-700">{{ $item }}</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-[20px] font-bold mb-4">Hosting</h3>
                <div class="w-full p-4 rounded-2xl bg-white border border-gray-100 shadow-lg shadow-gray-200/50 flex items-center justify-between">
                    <div>
                        <p class="text-[15px] font-bold">Switch to hosting</p>
                        <p class="text-gray-500 text-[13px]">Manage your properties</p>
                    </div>
                    <a href="{{ route('host.dashboard') }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold">Switch</a>
                </div>
            </div>

            <div class="pt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left font-bold text-red-500 underline text-[15px] active:text-red-700 transition-colors">Log out</button>
                </form>
            </div>
        </div>
    </div>
@endsection
