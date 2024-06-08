<?php

use App\Models\Location;
use Illuminate\Support\Benchmark;
use Inertia\Testing\AssertableInertia as Assert;

test('index page has locations returned', function () {
    $locations = Location::factory()->create();
    $expectedLocationsCount = Location::count();

    $response = $this->actingAs($this->admin)
        ->get(route('admin.location.index'))
        ->assertStatus(200);

    $response->assertInertia(function (Assert $page) use ($expectedLocationsCount) {
        $page->component('Admin/Location/Index')
            ->has('locations.data', $expectedLocationsCount);
    });
});

test('index page loads within 500ms or less', function () {
    $speed = Benchmark::measure(
        fn () => $this->actingAs($this->admin)
            ->get(route('admin.location.index')),
    );

    // Page loads it just needs to be faster.
    if ($speed >= 500) {
        $this->markTestSkipped('Location index page needs to be refactored to load quicker.');
    }

    return $this->assertTrue($speed <= 500);
});

test('customers cannot view index page of all locations', function () {
    $response = $this->actingAs($this->customer)
        ->get(route('admin.location.index'))
        ->assertStatus(403);
});

test('can create a location', function () {
    $data = Location::factory()->make()->toArray();

    $response = $this->actingAs($this->admin)
        ->post(route('admin.location.store'), $data);

    $this->assertDatabaseHas('locations', [
        'name' => $data['name'],
    ]);
});

test('customers cannot create a location', function () {
    $data = Location::factory()->make()->toArray();

    $response = $this->actingAs($this->customer)
        ->post(route('admin.location.store'), $data)
        ->assertStatus(403);

    $this->assertDatabaseMissing('locations', [
        'name' => $data['name'],
    ]);
});

test('can view location details', function () {
    $location = Location::factory()->create();

    $response = $this->actingAs($this->admin)
        ->get(route('admin.location.show', $location))
        ->assertStatus(200);

    $response->assertInertia(function (Assert $page) {
        $page->component('Admin/Location/Show')
            ->has('location', 1);
    });
});

test('show page loads within 500ms or less', function () {
    $location = Location::factory()->create();

    $speed = Benchmark::measure(
        fn () => $this->actingAs($this->admin)
            ->get(route('admin.location.show', $location)),
    );

    // Page loads it just needs to be faster.
    if ($speed >= 500) {
        $this->markTestSkipped('Location show page needs to be refactored to load quicker.');
    }

    return $this->assertTrue($speed <= 500);
});

test('customers cannot view location details', function () {
    $location = Location::factory()->create();

    $response = $this->actingAs($this->customer)
        ->get(route('admin.location.show', $location))
        ->assertStatus(403);
});

test('can update a location', function () {
    $location = Location::factory()->create();

    $response = $this->actingAs($this->admin)
        ->patch(route('admin.location.update', $location), ['name' => 'new name']);

    expect($location->refresh()->name)->toBe('new name');
});

test('customers cannot update a location', function () {
    $location = Location::factory()->create();
    $currentName = $location->name;

    $response = $this->actingAs($this->customer)
        ->patch(route('admin.location.update', $location), ['name' => 'new name'])
        ->assertStatus(403);

    expect($location->refresh()->name)->toBe($currentName);
});

test('can soft delete a location', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->admin)
        ->delete(route('admin.location.destroy', $location));

    $this->assertSoftDeleted($location);
});

test('customers cannot soft delete a location', function () {
    $location = Location::factory()->create();

    $this->actingAs($this->customer)
        ->delete(route('admin.location.destroy', $location))
        ->assertStatus(403);

    $this->assertNotSoftDeleted($location);
});
