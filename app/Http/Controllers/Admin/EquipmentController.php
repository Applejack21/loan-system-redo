<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Equipment\CreateEquipment;
use App\Actions\Equipment\DeleteEquipment;
use App\Actions\Equipment\GetEquipments;
use App\Actions\Equipment\UpdateEquipment;
use App\Http\Controllers\Controller;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EquipmentController extends Controller
{
	/**
	 * Create the controller instance.
	 * This automatically applies the equipment policies to the default functions this controller has.
	 */
	public function __construct()
	{
		$this->authorizeResource(Equipment::class, 'equipment');
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		return Inertia::render('Admin/Equipment/Index', [
			'title' => 'Equipments',
			'equipments' => fn () => (new GetEquipments())->execute($request, ['category', 'location']),
			'filters' => $request->only('search'),
			'breadcrumbs' => [
				'Admin Dashboard' => route('admin.dashboard.index'),
				'Equipments' => null,
			],
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$equipment = (new CreateEquipment())->execute($request->all());

		return redirect()->back()->with('message', [
			'type' => 'success',
			'message' => 'Equipment created successfully.',
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Equipment $equipment)
	{
		return Inertia::render('Admin/Equipment/Show', [
			'title' => $equipment->name,
			'equipment' => new EquipmentResource($equipment->loadMissing('createdBy', 'updatedBy', 'category', 'location')),
			'breadcrumbs' => [
				'Admin Dashboard' => route('admin.dashboard.index'),
				'Equipments' => route('admin.equipment.index'),
				$equipment->name => null,
			],
		]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Equipment $equipment)
	{
		$equipment = (new UpdateEquipment())->execute($equipment, $request->all());

		return redirect()->back()->with('message', [
			'type' => 'success',
			'message' => 'Equipment updated successfully.',
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Equipment $equipment)
	{
		$equipment = (new DeleteEquipment())->execute($equipment);

		return redirect()->route('admin.category.index')->with('message', [
			'type' => 'success',
			'message' => 'Equipment deleted successfully.',
		]);
	}
}
