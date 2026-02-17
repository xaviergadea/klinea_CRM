<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['nullable', 'string', 'in:admin,manager,sales,viewer'],
            'phone'    => ['nullable', 'string', 'max:50'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => User::class,
            'loggable_id'   => $user->id,
            'type'          => 'created',
            'description'   => "S'ha creat l'usuari: {$user->name}",
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Usuari creat correctament.');
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role'     => ['nullable', 'string', 'in:admin,manager,sales,viewer'],
            'phone'    => ['nullable', 'string', 'max:50'],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => User::class,
            'loggable_id'   => $user->id,
            'type'          => 'updated',
            'description'   => "S'ha actualitzat l'usuari: {$user->name}",
        ]);

        return redirect()->back()
            ->with('success', 'Usuari actualitzat correctament.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return redirect()->back()
                ->with('error', 'No pots eliminar el teu propi compte.');
        }

        // Log activity before deletion
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => User::class,
            'loggable_id'   => $user->id,
            'type'          => 'deleted',
            'description'   => "S'ha eliminat l'usuari: {$user->name}",
        ]);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuari eliminat correctament.');
    }
}
