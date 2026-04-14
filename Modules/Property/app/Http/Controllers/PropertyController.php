<?php

namespace Modules\Property\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Property\Http\Requests\StorePropertyRequest;
use Modules\Property\Http\Requests\UpdatePropertyRequest;
use Modules\Property\Services\PropertyService;
use App\Http\Resources\PropertyResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PropertyController extends Controller
{
    public function __construct(private PropertyService $propertyService) {}

    public function index(): AnonymousResourceCollection
    {
        $properties = $this->propertyService->getAllProperties(request()->all());
        
        return PropertyResource::collection($properties);
    }

    public function show(string $id): JsonResponse|PropertyResource
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        return new PropertyResource($property);
    }

    public function store(StorePropertyRequest $request): JsonResponse
    {
        $property = $this->propertyService->createProperty($request->validated(), $request->user()->id);
        $property->load(['host', 'images', 'primaryImage']);

        return response()->json([
            'message' => 'Property created successfully',
            'data' => new PropertyResource($property)
        ], 201);
    }

    public function update(UpdatePropertyRequest $request, string $id): JsonResponse|PropertyResource
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        $this->propertyService->updateProperty($property, $request->validated());

        return new PropertyResource($property->refresh()->load(['host', 'images', 'primaryImage']));
    }

    public function destroy(string $id): JsonResponse
    {
        $property = $this->propertyService->getPropertyById($id);

        if (!$property) {
            return response()->json(['message' => 'Property not found'], 404);
        }

        $this->propertyService->deleteProperty($property);

        return response()->json(['message' => 'Property deleted successfully']);
    }
}
