<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with optional filters.
     */
    public function index(Request $request)
    {
        $query = Client::with('assignedTo');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Search by name, email, or company
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('tax_id', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('clients.create', compact('users'));
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'company'       => ['nullable', 'string', 'max:255'],
            'tax_id'        => ['nullable', 'string', 'max:50'],
            'address'       => ['nullable', 'string', 'max:500'],
            'city'          => ['nullable', 'string', 'max:255'],
            'postal_code'   => ['nullable', 'string', 'max:20'],
            'country'       => ['nullable', 'string', 'max:100'],
            'type'          => ['nullable', 'string', 'in:individual,company'],
            'assigned_to'   => ['nullable', 'exists:users,id'],
            'notes'         => ['nullable', 'string'],
        ]);

        $client = Client::create($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Client::class,
            'loggable_id'   => $client->id,
            'type'          => 'created',
            'description'   => "S'ha creat el client: {$client->name}",
        ]);

        return redirect()->route('clients.index')
            ->with('success', 'Client creat correctament.');
    }

    /**
     * Display the specified client.
     */
    public function show($id)
    {
        $client = Client::with([
            'assignedTo',
            'opportunities',
            'budgets',
            'activities.user',
        ])->findOrFail($id);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('clients.edit', compact('client', 'users'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'company'       => ['nullable', 'string', 'max:255'],
            'tax_id'        => ['nullable', 'string', 'max:50'],
            'address'       => ['nullable', 'string', 'max:500'],
            'city'          => ['nullable', 'string', 'max:255'],
            'postal_code'   => ['nullable', 'string', 'max:20'],
            'country'       => ['nullable', 'string', 'max:100'],
            'type'          => ['nullable', 'string', 'in:individual,company'],
            'assigned_to'   => ['nullable', 'exists:users,id'],
            'notes'         => ['nullable', 'string'],
        ]);

        $client->update($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Client::class,
            'loggable_id'   => $client->id,
            'type'          => 'updated',
            'description'   => "S'ha actualitzat el client: {$client->name}",
        ]);

        return redirect()->back()
            ->with('success', 'Client actualitzat correctament.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        // Log activity before deletion
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Client::class,
            'loggable_id'   => $client->id,
            'type'          => 'deleted',
            'description'   => "S'ha eliminat el client: {$client->name}",
        ]);

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client eliminat correctament.');
    }
}
