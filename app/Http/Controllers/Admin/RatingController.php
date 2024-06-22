<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Rating\CreateRating;
use App\Actions\Rating\DeleteRating;
use App\Actions\Rating\GetRatings;
use App\Actions\Rating\UpdateRating;
use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RatingController extends Controller
{
	/**
	 * Create the controller instance.
	 * This automatically applies the rating policies to the default functions this controller has.
	 */
	public function __construct()
	{
		$this->authorizeResource(Rating::class, 'rating');
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		return Inertia::render('Admin/Rating/Index', [
			'title' => 'Ratings',
			'filters' => $request->only('search'),
			'breadcrumbs' => [
				'Dashboard' => route('admin.dashboard.index'),
				'Ratings' => null,
			],
			'ratings' => fn () => (new GetRatings())->execute($request, load: ['createdBy', 'equipment']),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$rating = (new CreateRating())->execute($request->all());

		return redirect()->back()->with('message', [
			'type' => 'success',
			'message' => 'Rating created successfully.',
		]);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Rating $rating)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Rating $rating)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Rating $rating)
	{
		$rating = (new UpdateRating())->execute($rating, $request->all());

		return redirect()->back()->with('message', [
			'type' => 'success',
			'message' => 'Rating updated successfully.',
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Rating $rating)
	{
		$rating = (new DeleteRating())->execute($rating);

		return redirect()->route('admin.rating.index')->with('message', [
			'type' => 'success',
			'message' => 'Rating deleted successfully.',
		]);
	}
}
