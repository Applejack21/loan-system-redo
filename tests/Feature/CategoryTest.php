<?php

use App\Models\Category;
use Inertia\Testing\AssertableInertia as Assert;

test('admin index page has categories returned', function () {
	$categories = Category::factory()->create();
	$expectedCategoriesCount = Category::count();

	$response = $this->actingAs($this->admin)
		->getJson(route('admin.category.index'))
		->assertStatus(200);

	$response->assertInertia(function (Assert $page) use ($expectedCategoriesCount) {
		$page->component('Admin/Category/Index')
			->has('categories.data', $expectedCategoriesCount);
	});
});

test('customers cannot view admin index page of all categories', function () {
	$response = $this->actingAs($this->customer)
		->getJson(route('admin.category.index'))
		->assertStatus(403);
});

test('can create a category', function () {
	$data = Category::factory()->make()->toArray();

	$response = $this->actingAs($this->admin)
		->post(route('admin.category.store'), $data);

	$this->assertDatabaseHas('categories', [
		'name' => $data['name'],
	]);
});

test('cannot create a category with the same name', function () {
	$category = Category::factory()->create();
	$data = Category::factory()->make(['name' => $category->name])->toArray();

	$response = $this->actingAs($this->admin)
		->post(route('admin.category.store'), $data);

	$this->assertDatabaseCount('categories', 1);
});

test('cannot create a category with the same slug', function () {
	$category = Category::factory()->create();
	$data = Category::factory()->make(['slug' => $category->name])->toArray();

	$response = $this->actingAs($this->admin)
		->post(route('admin.category.store'), $data);

	$this->assertDatabaseCount('categories', 1);
});

test('customers cannot create a category', function () {
	$data = Category::factory()->make()->toArray();

	$response = $this->actingAs($this->customer)
		->post(route('admin.category.store'), $data)
		->assertStatus(403);

	$this->assertDatabaseMissing('categories', [
		'name' => $data['name'],
	]);
});

test('can view admin category details', function () {
	$category = Category::factory()->create();

	$response = $this->actingAs($this->admin)
		->get(route('admin.category.show', $category))
		->assertStatus(200);

	$response->assertInertia(function (Assert $page) {
		$page->component('Admin/Category/Show')
			->has('category', 1);
	});
});

test('customers cannot view admin category details', function () {
	$category = Category::factory()->create();

	$response = $this->actingAs($this->customer)
		->get(route('admin.category.show', $category))
		->assertStatus(403);
});

test('can update a category', function () {
	$category = Category::factory()->create();

	$response = $this->actingAs($this->admin)
		->patch(route('admin.category.update', $category), ['name' => 'new name',]);

	expect($category->refresh()->name)->toBe('new name');
});

test('cannot update a category with the same name', function () {
	$category = Category::factory()->create();
	$category2 = Category::factory()->create();
	$originalName = $category2->name;

	// update category 2 to have same name as category
	$response = $this->actingAs($this->admin)
		->patch(route('admin.category.update', $category2), ['name' => $category->name]);

	expect($category2->refresh()->name)->toBe($originalName);
});

test('cannot update a category with the same slug', function () {
	$category = Category::factory()->create();
	$category2 = Category::factory()->create();
	$originalSlug = $category2->slug;

	// update category 2 to have same slug as category
	$response = $this->actingAs($this->admin)
		->patch(route('admin.category.update', $category2), ['slug' => $category->slug]);

	expect($category2->refresh()->slug)->toBe($originalSlug);
});

test('customers cannot update a category', function () {
	$category = Category::factory()->create();
	$currentName = $category->name;

	$response = $this->actingAs($this->customer)
		->patch(route('admin.category.update', $category), ['name' => 'new name'])
		->assertStatus(403);

	expect($category->refresh()->name)->toBe($currentName);
});

test('can soft delete a category', function () {
	$category = Category::factory()->create();

	$this->actingAs($this->admin)
		->delete(route('admin.category.destroy', $category));

	$this->assertSoftDeleted($category);
});

test('customers cannot soft delete a category', function () {
	$category = Category::factory()->create();

	$this->actingAs($this->customer)
		->delete(route('admin.category.destroy', $category))
		->assertStatus(403);

	$this->assertNotSoftDeleted($category);
});
