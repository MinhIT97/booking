<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
