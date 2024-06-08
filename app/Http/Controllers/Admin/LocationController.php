<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Location\CreateLocation;
use App\Actions\Location\DeleteLocation;
use App\Actions\Location\GetLocations;
use App\Actions\Location\UpdateLocation;
use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentResource;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    /**
     * Create the controller instance.
     * This automatically applies the location policies to the default functions this controller has.
     */
    public function __construct()
    {
        $this->authorizeResource(Location::class, 'location');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Location/Index', [
            'title' => 'Locations',
            'filters' => $request->only('search'),
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Locations' => null,
            ],
            'locations' => fn () => (new GetLocations())->execute($request, count: ['equipments']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $location = (new CreateLocation())->execute($request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'Location created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        $equipments = $location->equipments()->orderBy('name')->paginate();

        return Inertia::render('Admin/Location/Show', [
            'title' => $location->name,
            'location' => fn () => new LocationResource($location->loadMissing('createdBy', 'updatedBy')),
            'equipments' => fn () => EquipmentResource::collection($equipments),
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Locations' => route('admin.location.index'),
                $location->name => null,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $location = (new UpdateLocation())->execute($location, $request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'Location updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location = (new DeleteLocation())->execute($location);

        return redirect()->route('admin.location.index')->with('message', [
            'type' => 'success',
            'message' => 'Location deleted successfully.',
        ]);
    }
}
