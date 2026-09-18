<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'role_id'  => 'required|exists:roles,id',
            'is_active'=> 'boolean',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role_id'   => $request->role_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('users.index')
                         ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::min(8)],
            'role_id'  => 'required|exists:roles,id',
            'is_active'=> 'boolean',
        ]);

        $user->update([
            'name'      => $request->name,
            'email'     => $request->email,
            'role_id'   => $request->role_id,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                             ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('users.index')
                         ->with('success', 'User deleted successfully!');
    }
}