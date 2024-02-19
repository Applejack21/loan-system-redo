<?php

namespace App\Http\Controllers\Admin;

use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\GetUsers;
use App\Actions\User\UpdateUser;
use App\Http\Controllers\Controller;
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
        return Inertia::render('Admin/User/Index', [
            'title' => 'Users',
            'users' => fn () => (new GetUsers())->execute($request),
            'filters' => $request->only('search', 'type'),
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Users' => null,
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = (new CreateUser())->execute($request->all());

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
        return Inertia::render('Admin/User/Show', [
            'title' => 'Viewing: ' . $user->name,
            'user' => new UserResource($user),
            'breadcrumbs' => [
                'Dashboard' => route('admin.dashboard.index'),
                'Users' => route('admin.user.index'),
                $user->name => null,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user = (new UpdateUser())->execute($user, $request->all());

        return redirect()->back()->with('message', [
            'type' => 'success',
            'message' => 'User updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user = (new DeleteUser())->execute($user);

        return redirect()->route('admin.user.index')->with('message', [
            'type' => 'success',
            'message' => 'User deleted successfully.',
        ]);
    }
}
