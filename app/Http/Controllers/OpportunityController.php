<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Client;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityController extends Controller
{
    /**
     * Display a listing of opportunities with optional filters.
     */
    public function index(Request $request)
    {
        $query = Opportunity::with(['client', 'assignedTo']);

        // Filter by stage
        if ($request->filled('stage')) {
            $query->where('stage', $request->input('stage'));
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        // Search by title
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%");
        }

        $opportunities = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('opportunities.index', compact('opportunities'));
    }

    /**
     * Show the form for creating a new opportunity.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();

        return view('opportunities.create', compact('users', 'clients'));
    }

    /**
     * Store a newly created opportunity in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'client_id'          => ['required', 'exists:clients,id'],
            'value'              => ['required', 'numeric', 'min:0'],
            'stage'              => ['required', 'string', 'in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost'],
            'probability'        => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'assigned_to'        => ['nullable', 'exists:users,id'],
            'description'        => ['nullable', 'string'],
            'notes'              => ['nullable', 'string'],
        ]);

        $opportunity = Opportunity::create($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Opportunity::class,
            'loggable_id'   => $opportunity->id,
            'type'          => 'created',
            'description'   => "S'ha creat l'oportunitat: {$opportunity->title}",
        ]);

        return redirect()->route('opportunities.index')
            ->with('success', 'Oportunitat creada correctament.');
    }

    /**
     * Display the specified opportunity.
     */
    public function show($id)
    {
        $opportunity = Opportunity::with([
            'client',
            'assignedTo',
            'budgets',
            'activities.user',
            'documents.user',
        ])->findOrFail($id);

        return view('opportunities.show', compact('opportunity'));
    }

    /**
     * Show the form for editing the specified opportunity.
     */
    public function edit($id)
    {
        $opportunity = Opportunity::with('documents.user')->findOrFail($id);
        $users = User::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();

        return view('opportunities.edit', compact('opportunity', 'users', 'clients'));
    }

    /**
     * Update the specified opportunity in storage.
     */
    public function update(Request $request, $id)
    {
        $opportunity = Opportunity::findOrFail($id);

        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'client_id'          => ['required', 'exists:clients,id'],
            'value'              => ['required', 'numeric', 'min:0'],
            'stage'              => ['required', 'string', 'in:prospecting,qualification,proposal,negotiation,closed_won,closed_lost'],
            'probability'        => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'assigned_to'        => ['nullable', 'exists:users,id'],
            'description'        => ['nullable', 'string'],
            'notes'              => ['nullable', 'string'],
        ]);

        $oldStage = $opportunity->stage;
        $opportunity->update($validated);

        // Log activity (with stage change detail if applicable)
        $description = "S'ha actualitzat l'oportunitat: {$opportunity->title}";
        if ($oldStage !== $opportunity->stage) {
            $description .= " (etapa: {$oldStage} -> {$opportunity->stage})";
        }

        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Opportunity::class,
            'loggable_id'   => $opportunity->id,
            'type'          => 'updated',
            'description'   => $description,
        ]);

        return redirect()->back()
            ->with('success', 'Oportunitat actualitzada correctament.');
    }

    /**
     * Remove the specified opportunity from storage.
     */
    public function destroy($id)
    {
        $opportunity = Opportunity::findOrFail($id);

        // Log activity before deletion
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Opportunity::class,
            'loggable_id'   => $opportunity->id,
            'type'          => 'deleted',
            'description'   => "S'ha eliminat l'oportunitat: {$opportunity->title}",
        ]);

        $opportunity->delete();

        return redirect()->route('opportunities.index')
            ->with('success', 'Oportunitat eliminada correctament.');
    }
}
