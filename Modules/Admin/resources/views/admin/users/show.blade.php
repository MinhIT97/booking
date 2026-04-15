@extends('admin::layouts.master')

@section('title', 'User Detail')
@section('header', $user->name)
@section('subheader', $user->email)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between gap-4">
                <h2 class="font-bold text-gray-900">Profile</h2>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-outline px-3 py-1.5 text-xs">Edit</a>
            </div>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-6 text-sm">
                <div><dt class="text-gray-400 mb-1">Name</dt><dd class="font-semibold text-gray-900">{{ $user->name }}</dd></div>
                <div><dt class="text-gray-400 mb-1">Email</dt><dd class="font-semibold text-gray-900">{{ $user->email }}</dd></div>
                <div><dt class="text-gray-400 mb-1">Phone</dt><dd class="font-semibold text-gray-900">{{ $user->phone ?? 'N/A' }}</dd></div>
                <div><dt class="text-gray-400 mb-1">Role</dt><dd class="font-semibold text-gray-900">{{ ucfirst($user->role->name ?? 'user') }}</dd></div>
                <div><dt class="text-gray-400 mb-1">Status</dt><dd class="font-semibold text-gray-900">{{ $user->status_label }}</dd></div>
                <div><dt class="text-gray-400 mb-1">Joined</dt><dd class="font-semibold text-gray-900">{{ $user->created_at->format('M d, Y') }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100"><h2 class="font-bold text-gray-900">Recent Bookings</h2></div>
            <div class="divide-y divide-gray-50">
                @forelse($user->bookings->take(8) as $booking)
                    <a href="{{ route('admin.bookings.show', $booking->id) }}" class="block px-6 py-4 hover:bg-gray-50">
                        <p class="font-semibold text-gray-900">{{ $booking->property->title ?? 'Property removed' }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->status_label }} · ${{ number_format($booking->total_price, 0) }}</p>
                    </a>
                @empty
                    <p class="px-6 py-10 text-sm text-gray-400 text-center">No bookings yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
            <div class="space-y-2">
                @if($user->status_key !== 'active')
                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">@csrf
                        <button class="btn-primary w-full justify-center">Activate</button>
                    </form>
                @endif
                @if($user->status_key !== 'blocked')
                    <form method="POST" action="{{ route('admin.users.block', $user->id) }}" onsubmit="return confirm('Block this user?')">@csrf
                        <button class="btn-outline w-full justify-center text-red-600 border-red-100">Block</button>
                    </form>
                @endif
                @if(auth()->id() !== $user->id)
                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                        @csrf @method('DELETE')
                        <button class="btn-outline w-full justify-center text-red-600 border-red-100">Delete User</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
