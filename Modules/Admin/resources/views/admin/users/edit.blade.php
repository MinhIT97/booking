@extends('admin::layouts.master')

@section('title', 'Edit User')
@section('header', 'Edit User')
@section('subheader', $user->email)

@section('content')
<form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="max-w-3xl bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-5">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Name</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input name="email" value="{{ old('email', $user->email) }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone</label>
            <input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
            @error('phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role</label>
            <select name="role" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
                @foreach(['admin' => 'Admin', 'host' => 'Host', 'user' => 'User'] as $value => $label)
                    <option value="{{ $value }}" {{ old('role', $user->role->name ?? 'user') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('role')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status</label>
            <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm">
                @foreach(['pending' => 'Pending', 'active' => 'Active', 'inactive' => 'Inactive', 'blocked' => 'Blocked'] as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $user->status_key) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="flex justify-between gap-3 pt-2">
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn-outline">Cancel</a>
        <button class="btn-primary">Save Changes</button>
    </div>
</form>
@endsection
