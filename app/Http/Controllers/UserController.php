<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('level')->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $levels = Level::all();
        return view('admin.users.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_level' => 'required|exists:levels,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'id_level' => $validated['id_level'],
        ]);

        return redirect()->route('users.index')->with('success', 'User ditambahkan');
    }

    public function edit(User $user)
    {
        $levels = Level::all();
        return view('admin.users.edit', compact('user', 'levels'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'id_level' => 'required|exists:levels,id',
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->id_level = $validated['id_level'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        return redirect()->route('users.index')->with('success', 'User diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User dihapus');
    }

    public function resetPasswordDefault(User $user)
    {
        $user->password = Hash::make('password123');
        $user->save();
        return redirect()->route('users.index')->with('success', 'Password user ' . $user->name . ' berhasil direset menjadi: password123');
    }
}
