@extends('admin::layouts.master')

@section('title', 'User Management')
@section('breadcrumb', 'Users')
@section('header', 'User Management')
@section('subheader', 'Manage all registered users and hosts.')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Total Users</p>
        <p class="text-3xl font-extrabold text-gray-900">{{ $users->total() }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Active Now</p>
        <p class="text-3xl font-extrabold text-emerald-600">{{ $users->where('status', 'active')->count() }}</p>
    </div>
</div>

{{-- FILTERS --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users…" 
                   class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand transition-all">
        </div>
        <div>
            <select name="role" onchange="this.form.submit()" class="w-full py-2.5 px-4 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand transition-all appearance-none cursor-pointer">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="host" {{ request('role') == 'host' ? 'selected' : '' }}>Host</option>
                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn-outline px-3 border-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>
    </form>
</div>

{{-- USER TABLE --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-4 text-left">User</th>
                    <th class="px-6 py-4 text-left">Role</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Joined</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-brand/10 text-brand font-bold flex items-center justify-center text-xs flex-shrink-0">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-900 truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->role->name === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role->name === 'host' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ ucfirst($user->role->name) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->status === 'active')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        @elseif($user->status === 'blocked')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Blocked
                            </span>
                        @elseif($user->status === 'pending')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-400">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($user->status === 'pending')
                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-outline px-3 py-1.5 border-green-100 text-green-600 hover:bg-green-50 text-[11px] uppercase tracking-wider font-bold">
                                        Approve
                                    </button>
                                </form>
                            @endif

                            @if($user->status === 'active')
                                <form action="{{ route('admin.users.block', $user->id) }}" method="POST" onsubmit="return confirm('Block this user?')">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    </button>
                                </form>
                            @endif

                            @if($user->status === 'blocked' || $user->status === 'inactive')
                                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-400 hover:text-green-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                </form>
                            @endif
                            
                            <a href="#" class="p-2 text-gray-400 hover:text-brand transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center text-gray-400">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
