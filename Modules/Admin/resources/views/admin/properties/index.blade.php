@extends('admin::layouts.master')

@section('title', 'Property Moderation')
@section('breadcrumb', 'Properties')
@section('header', 'Property Moderation')
@section('subheader', 'Approve, reject, or delete rental properties.')

@section('content')

{{-- STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Total Properties</p>
        <p class="text-3xl font-extrabold text-gray-900">{{ $properties->total() }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Awaiting Approval</p>
        <p class="text-3xl font-extrabold text-amber-500">{{ $properties->where('status_key', 'draft')->count() }}</p>
    </div>
</div>

{{-- FILTERS --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6">
    <form action="{{ route('admin.properties.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, city or host…" 
                   class="w-full pl-4 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand transition-all">
        </div>
        <div>
            <select name="status" onchange="this.form.submit()" class="w-full py-2.5 px-4 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand transition-all appearance-none cursor-pointer">
                <option value="">All Statuses</option>
                <option value="draft" {{ in_array(request('status'), ['draft', 'pending'], true) ? 'selected' : '' }}>Draft</option>
                <option value="active" {{ in_array(request('status'), ['active', 'approved'], true) ? 'selected' : '' }}>Active</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="btn-primary flex-1 justify-center">Filter</button>
            <a href="{{ route('admin.properties.index') }}" class="btn-outline px-3 border-gray-100 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>
    </form>
</div>

{{-- PROPERTY TABLE --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-4 text-left">Property</th>
                    <th class="px-6 py-4 text-left">Host</th>
                    <th class="px-6 py-4 text-left">Price/Night</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($properties as $property)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($property->primaryImage)
                                    <img src="{{ $property->primaryImage->url }}" alt="{{ $property->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xl text-gray-300">🏠</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-900 truncate">{{ $property->title }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $property->city }}, {{ $property->country }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-400">
                                {{ substr($property->host->name ?? '?', 0, 1) }}
                            </div>
                            <span class="text-gray-600 font-medium">{{ $property->host->name ?? 'Unknown Host' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-900">
                        ${{ number_format($property->price_per_night, 0) }}
                    </td>
                    <td class="px-6 py-4">
                        @if($property->status_key === 'active')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                Active
                            </span>
                        @elseif($property->status_key === 'rejected')
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                Rejected
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-amber-500">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if($property->status_key === 'draft')
                                <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-outline px-3 py-1.5 border-green-100 text-green-600 hover:bg-green-50 text-[11px] uppercase tracking-wider font-bold">
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-outline px-3 py-1.5 border-red-100 text-red-600 hover:bg-red-50 text-[11px] uppercase tracking-wider font-bold">
                                        Reject
                                    </button>
                                </form>
                            @endif

                            @if($property->status_key === 'active')
                                <form action="{{ route('admin.properties.reject', $property->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="Reject Property">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    </button>
                                </form>
                            @endif

                            @if($property->status_key === 'rejected')
                                <form action="{{ route('admin.properties.approve', $property->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-400 hover:text-green-500 transition-colors" title="Approve Property">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                </form>
                            @endif
                            
                            <form action="{{ route('admin.properties.destroy', $property->id) }}" method="POST" onsubmit="return confirm('Delete this property?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center text-gray-400">
                        No properties found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($properties->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
        {{ $properties->links() }}
    </div>
    @endif
</div>
@endsection
