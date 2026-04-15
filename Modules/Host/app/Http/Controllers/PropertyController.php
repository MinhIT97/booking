<?php

namespace Modules\Host\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Property\Services\PropertyService;
use Modules\Property\Http\Requests\StorePropertyRequest;
use Modules\Property\Http\Requests\UpdatePropertyRequest;

class PropertyController extends Controller
{
    public function __construct(
        private readonly PropertyService $propertyService
    ) {}

    /**
     * GET /host/properties
     * Display paginated list of the authenticated host's properties.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['q', 'type', 'status']);

        $properties = $this->propertyService->getByHost(
            hostId: auth()->id(),
            filters: $filters,
        );

        return view('host::properties.index', compact('properties'));
    }

    /**
     * GET /host/properties/create
     * Show the "add property" form.
     */
    public function create(): View
    {
        return view('host::properties.create');
    }

    /**
     * POST /host/properties
     * Validate, create the property and redirect.
     */
    public function store(StorePropertyRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['status'] = $request->input('status', '1'); // 'active' or 'draft'

        $property = $this->propertyService->createProperty($validated, auth()->id());

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("properties/{$property->id}", 'public');
                $property->images()->create([
                    'url'        => asset("storage/{$path}"),
                    'is_primary' => $property->images()->count() === 0,
                ]);
            }
        }

        return redirect()
            ->route('host.properties.index')
            ->with('success', 'Property "' . $property->title . '" created successfully!');
    }

    /**
     * GET /host/properties/{id}
     * Show a single property with its bookings.
     */
    public function show(string $id): View|RedirectResponse
    {
        $property = $this->propertyService->getPropertyById($id);

        if (! $property || $property->host_id !== auth()->id()) {
            return redirect()->route('host.properties.index')
                ->with('error', 'Property not found.');
        }

        $property->load(['images', 'bookings.user']);

        return view('host::properties.show', compact('property'));
    }

    /**
     * GET /host/properties/{id}/edit
     * Show the edit form pre-populated with existing data.
     */
    public function edit(string $id): View|RedirectResponse
    {
        $property = $this->propertyService->getPropertyById($id);

        if (! $property || $property->host_id !== auth()->id()) {
            return redirect()->route('host.properties.index')
                ->with('error', 'Property not found.');
        }

        $property->load(['images', 'amenities']);

        return view('host::properties.edit', compact('property'));
    }

    /**
     * PUT /host/properties/{id}
     * Update property data and redirect.
     */
    public function update(UpdatePropertyRequest $request, string $id): RedirectResponse
    {
        $property = $this->propertyService->getPropertyById($id);

        if (! $property || $property->host_id !== auth()->id()) {
            return redirect()->route('host.properties.index')
                ->with('error', 'Property not found.');
        }

        $this->propertyService->updateProperty($property, $request->validated());

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store("properties/{$property->id}", 'public');
                $property->images()->create([
                    'url'        => asset("storage/{$path}"),
                    'is_primary' => false,
                ]);
            }
        }

        return redirect()
            ->route('host.properties.show', $property->id)
            ->with('success', 'Property updated successfully!');
    }

    /**
     * DELETE /host/properties/{id}
     * Soft-delete a property and redirect.
     */
    public function destroy(string $id): RedirectResponse
    {
        $property = $this->propertyService->getPropertyById($id);

        if (! $property || $property->host_id !== auth()->id()) {
            return redirect()->route('host.properties.index')
                ->with('error', 'Property not found.');
        }

        $this->propertyService->deleteProperty($property);

        return redirect()
            ->route('host.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}
