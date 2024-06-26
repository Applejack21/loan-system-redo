<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Category\CreateCategory;
use App\Actions\Category\DeleteCategory;
use App\Actions\Category\GetCategories;
use App\Actions\Category\UpdateCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\EquipmentResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Create the controller instance.
     * This automatically applies the category policies to the default functions this controller has.
     */
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Admin/Category/Index', [
            'title' => 'Categories',
            'filters' => $request->only('search'),
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Categories' => null,
            ],
            'categories' => fn () => (new GetCategories())->execute($request, count: ['equipments']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $category = (new CreateCategory())->execute($request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'Category created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $equipments = $category->equipments()->orderBy('name')->paginate();

        return Inertia::render('Admin/Category/Show', [
            'title' => $category->name,
            'category' => fn () => new CategoryResource($category->loadMissing('createdBy', 'updatedBy', 'equipments')),
            'equipments' => fn () => EquipmentResource::collection($equipments),
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Categories' => route('admin.category.index'),
                $category->name => null,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $category = (new UpdateCategory())->execute($category, $request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'Category updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category = (new DeleteCategory())->execute($category);

        return redirect()->route('admin.category.index')->with('message', [
            'type' => 'success',
            'message' => 'Category deleted successfully.',
        ]);
    }
}
