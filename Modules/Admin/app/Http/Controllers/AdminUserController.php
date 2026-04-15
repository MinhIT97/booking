<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Admin\Services\AdminUserService;

class AdminUserController extends Controller
{
    public function __construct(protected AdminUserService $userService) {}

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['role', 'status', 'search']);
        $users = $this->userService->getUserList($filters);

        return view('admin::admin.users.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = $this->userService->getUser($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }

        return view('admin::admin.users.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = $this->userService->getUser($id);

        if (!$user) {
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }

        return view('admin::admin.users.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'role' => ['required', 'in:admin,host,user'],
            'status' => ['required', 'in:pending,active,inactive,blocked,1,2,3,4'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $success = $this->userService->updateUser($id, $validated);

        return $success
            ? redirect()->route('admin.users.show', $id)->with('success', 'User updated successfully.')
            : back()->with('error', 'Failed to update user.')->withInput();
    }

    /**
     * Toggle user activation status.
     */
    public function toggleStatus(string $id)
    {
        $success = $this->userService->toggleUserStatus($id);
        
        if ($success) {
            return back()->with('success', 'User status updated successfully.');
        }

        return back()->with('error', 'Failed to update user status.');
    }

    /**
     * Approve a user (e.g. Host).
     */
    public function approve(string $id)
    {
        $success = $this->userService->approveUser($id);
        
        if ($success) {
            return back()->with('success', 'User account approved successfully.');
        }

        return back()->with('error', 'Failed to approve user.');
    }

    /**
     * Block a user.
     */
    public function block(string $id)
    {
        $success = $this->userService->blockUser($id);
        
        if ($success) {
            return back()->with('success', 'User has been blocked.');
        }

        return back()->with('error', 'Failed to block user.');
    }

    public function destroy(string $id)
    {
        $success = $this->userService->deleteUser($id, auth()->id());

        return $success
            ? redirect()->route('admin.users.index')->with('success', 'User deleted successfully.')
            : back()->with('error', 'Failed to delete user.');
    }
}
