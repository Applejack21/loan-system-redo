<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('index page has users returned', function () {
	$users = User::factory()->create();
	$expectedUsersCount = User::count();

	$response = $this->actingAs($this->admin)
		->getJson(route('user.index'))
		->assertStatus(200);

	$response->assertInertia(function (Assert $page) use ($expectedUsersCount) {
		$page->component('User/Index')
			->has('users.data', $expectedUsersCount);
	});
});

test('customers cannot view index page of all users', function () {
	$expectedUsersCount = User::count();

	$response = $this->actingAs($this->customer)
		->getJson(route('user.index'))
		->assertStatus(403);
});

test('can create a user', function () {
	$data = [
		'name' => 'Test User',
		'email' => 'test@example.com',
		'password' => 'Password1!',
		'password_confirmation' => 'Password1!',
		'role' => 'customer',
	];

	$response = $this->actingAs($this->admin)
		->post(route('user.store'), $data);

	$this->assertDatabaseHas('users', [
		'email' => $data['email'],
	]);
});

test('cannot create a user with the same email', function () {
	$data = [
		'name' => 'Test User',
		'email' => 'test@example.com',
		'password' => 'Password1!',
		'password_confirmation' => 'Password1!',
		'role' => 'customer',
	];

	$response = $this->actingAs($this->admin)
		->post(route('user.store'), $data);

	$this->assertDatabaseHas('users', [
		'email' => $data['email'],
	]);

	$data['name'] = 'New user same email';

	$response = $this->actingAs($this->admin)
		->post(route('user.store'), $data);

	$this->assertDatabaseMissing('users', [
		'email' => $data['name'],
	]);
});

test('customers cannot create a user', function () {
	$data = User::factory()->make()->toArray();

	$response = $this->actingAs($this->customer)
		->post(route('user.store'), $data)
		->assertStatus(403);

	$this->assertDatabaseMissing('users', [
		'email' => $data['email'],
	]);
});

test('can view user details', function () {
	$user = User::factory()->create();

	$response = $this->actingAs($this->admin)
		->get(route('user.show', $user))
		->assertStatus(200);

	$response->assertInertia(function (Assert $page) {
		$page->component('User/Show')
			->has('user', 1);
	});
});

test('customers cannot view user details', function () {
	$user = User::factory()->create();

	$response = $this->actingAs($this->customer)
		->get(route('user.show', $user))
		->assertStatus(403);
});

test('can update a user', function () {
	$user = User::factory()->create();

	$response = $this->actingAs($this->admin)
		->patch(route('user.update', $user), ['name' => 'new name', 'email' => $user->email, 'role' => $user->role]);

	expect($user->refresh()->name)->toBe('new name');
});

test('cannot update a user with the same email', function () {
	$user = User::factory()->create();
	$user2 = User::factory()->create();
	$originalEmail = $user2->email;

	// update user 2 to have same email as user
	$response = $this->actingAs($this->admin)
		->patch(route('user.update', $user2), ['name' => $user2->name, 'email' => $user->email, 'role' => $user2->role]);

	expect($user2->refresh()->email)->toBe($originalEmail);
});

test('customers cannot update a user', function () {
	$user = User::factory()->create();
	$currentName = $user->name;

	$response = $this->actingAs($this->customer)
		->patch(route('user.update', $user), ['name' => 'new name'])
		->assertStatus(403);

	expect($user->refresh()->name)->toBe($currentName);
});

test('can soft delete a user', function () {
	$user = User::factory()->create();

	$this->actingAs($this->admin)
		->delete(route('user.destroy', $user));

	$this->assertSoftDeleted($user);
});

test('customers cannot soft delete a user', function () {
	$user = User::factory()->create();

	$this->actingAs($this->customer)
		->delete(route('user.destroy', $user))
		->assertStatus(403);

	$this->assertNotSoftDeleted($user);
});
