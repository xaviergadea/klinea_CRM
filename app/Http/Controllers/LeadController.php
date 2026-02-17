<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Display a listing of leads with optional filters.
     */
    public function index(Request $request)
    {
        $query = Lead::with('assignedTo');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->input('source'));
        }

        // Search by name, email, or company
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new lead.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();

        return view('leads.create', compact('users'));
    }

    /**
     * Store a newly created lead in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'company'        => ['nullable', 'string', 'max:255'],
            'position'       => ['nullable', 'string', 'max:255'],
            'source'         => ['nullable', 'string', 'max:100'],
            'status'         => ['required', 'string', 'in:new,contacted,qualified,unqualified,converted'],
            'assigned_to'    => ['nullable', 'exists:users,id'],
            'notes'          => ['nullable', 'string'],
        ]);

        $lead = Lead::create($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Lead::class,
            'loggable_id'   => $lead->id,
            'type'          => 'created',
            'description'   => "S'ha creat el lead: {$lead->first_name} {$lead->last_name}",
        ]);

        return redirect()->route('leads.index')
            ->with('success', 'Lead creat correctament.');
    }

    /**
     * Display the specified lead.
     */
    public function show($id)
    {
        $lead = Lead::with(['assignedTo', 'activities.user'])->findOrFail($id);

        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified lead.
     */
    public function edit($id)
    {
        $lead = Lead::findOrFail($id);
        $users = User::orderBy('name')->get();

        return view('leads.edit', compact('lead', 'users'));
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validated = $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'last_name'      => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'company'        => ['nullable', 'string', 'max:255'],
            'position'       => ['nullable', 'string', 'max:255'],
            'source'         => ['nullable', 'string', 'max:100'],
            'status'         => ['required', 'string', 'in:new,contacted,qualified,unqualified,converted'],
            'assigned_to'    => ['nullable', 'exists:users,id'],
            'notes'          => ['nullable', 'string'],
        ]);

        $lead->update($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Lead::class,
            'loggable_id'   => $lead->id,
            'type'          => 'updated',
            'description'   => "S'ha actualitzat el lead: {$lead->first_name} {$lead->last_name}",
        ]);

        return redirect()->back()
            ->with('success', 'Lead actualitzat correctament.');
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);

        // Log activity before deletion
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Lead::class,
            'loggable_id'   => $lead->id,
            'type'          => 'deleted',
            'description'   => "S'ha eliminat el lead: {$lead->first_name} {$lead->last_name}",
        ]);

        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead eliminat correctament.');
    }
}
