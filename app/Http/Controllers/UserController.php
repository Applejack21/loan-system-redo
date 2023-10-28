<?php

namespace App\Http\Controllers;

use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\GetUsers;
use App\Actions\User\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Create the controller instance.
     * This automatically applies the user policies to the default functions this controller has.
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = (new GetUsers())->execute();

        return Inertia::render('User/Index', [
            'title' => 'Users',
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateUser $createUser)
    {
        $user = $createUser->execute($request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'User created successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return Inertia::render('User/Show', [
            'title' => 'Viewing - ' . $user->name,
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, UpdateUser $updateUser)
    {
        $user = $updateUser->execute($user, $request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'User updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteUser $deleteUser)
    {
        $user = $deleteUser->execute($user);

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }
}
