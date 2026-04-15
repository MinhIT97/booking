<?php

namespace Modules\Mobile\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Property\Services\PropertyService;
use Modules\Property\Enums\PropertyStatus;

class MobileController extends Controller
{
    public function __construct(private PropertyService $propertyService) {}

    /**
     * Mobile Webview Home Screen.
     */
    public function home(Request $request)
    {
        $categories = $this->propertyService->getPropertyTypes();

        $properties = $this->propertyService->getAllProperties([
            'status' => PropertyStatus::Active->value,
            'type_slug' => $request->query('type'),
            ...$request->all()
        ]);
        
        return view('mobile::home', compact('properties', 'categories'));
    }

    public function detail($id)
    {
        $property = $this->propertyService->getPropertyById($id);
        return view('mobile::detail', compact('property'));
    }

    public function booking($property_id)
    {
        $property = $this->propertyService->getPropertyById($property_id);
        return view('mobile::booking', compact('property'));
    }
}
