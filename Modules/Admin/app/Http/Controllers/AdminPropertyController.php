<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Services\AdminPropertyService;

class AdminPropertyController extends Controller
{
    public function __construct(protected AdminPropertyService $propertyService) {}

    /**
     * Display a listing of properties.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search']);
        $properties = $this->propertyService->getPropertyList($filters);

        return view('admin::admin.properties.index', compact('properties'));
    }

    public function show(string $id)
    {
        $property = $this->propertyService->getProperty($id);

        if (!$property) {
            return redirect()->route('admin.properties.index')->with('error', 'Property not found.');
        }

        return view('admin::admin.properties.show', compact('property'));
    }

    /**
     * Approve a property.
     */
    public function approve(string $id)
    {
        $success = $this->propertyService->approveProperty($id);
        
        if ($success) {
            return back()->with('success', 'Property approved successfully.');
        }

        return back()->with('error', 'Failed to approve property.');
    }

    /**
     * Reject a property.
     */
    public function reject(string $id)
    {
        $success = $this->propertyService->rejectProperty($id);
        
        if ($success) {
            return back()->with('success', 'Property has been rejected.');
        }

        return back()->with('error', 'Failed to reject property.');
    }

    /**
     * Delete a property.
     */
    public function destroy(string $id)
    {
        $success = $this->propertyService->deleteProperty($id);
        
        if ($success) {
            return back()->with('success', 'Property deleted successfully.');
        }

        return back()->with('error', 'Failed to delete property.');
    }
}
