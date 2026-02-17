<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\Opportunity;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
    /**
     * Display a listing of budgets with optional filters.
     */
    public function index(Request $request)
    {
        $query = Budget::with(['client', 'assignedTo']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->input('client_id'));
        }

        // Search by reference or title
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $budgets = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new budget.
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $opportunities = Opportunity::orderBy('title')->get();

        return view('budgets.create', compact('users', 'clients', 'opportunities'));
    }

    /**
     * Store a newly created budget in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'       => ['required', 'exists:clients,id'],
            'opportunity_id'  => ['nullable', 'exists:opportunities,id'],
            'tax_rate'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'subtotal'        => ['nullable', 'numeric', 'min:0'],
            'tax_amount'      => ['nullable', 'numeric', 'min:0'],
            'total'           => ['nullable', 'numeric', 'min:0'],
            'status'          => ['required', 'string', 'in:draft,sent,accepted,rejected,expired'],
            'valid_until'     => ['nullable', 'date'],
            'assigned_to'     => ['nullable', 'exists:users,id'],
            'description'     => ['nullable', 'string'],
            'notes'           => ['nullable', 'string'],
            'items'           => ['nullable', 'string'],
        ]);

        // Decode items JSON string
        if (!empty($validated['items'])) {
            $validated['items'] = json_decode($validated['items'], true);
        }

        // Auto-generate reference in KLN-YYYY-XXXX format
        $year = now()->year;
        $lastBudget = Budget::where('reference', 'like', "KLN-{$year}-%")
            ->orderBy('reference', 'desc')
            ->first();

        if ($lastBudget) {
            $lastNumber = (int) substr($lastBudget->reference, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $validated['reference'] = sprintf('KLN-%d-%04d', $year, $nextNumber);

        $budget = Budget::create($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Budget::class,
            'loggable_id'   => $budget->id,
            'type'          => 'created',
            'description'   => "S'ha creat el pressupost: {$budget->reference}",
        ]);

        return redirect()->route('budgets.index')
            ->with('success', 'Pressupost creat correctament.');
    }

    /**
     * Display the specified budget.
     */
    public function show($id)
    {
        $budget = Budget::with([
            'client',
            'opportunity',
            'assignedTo',
            'activities.user',
            'documents.user',
        ])->findOrFail($id);

        return view('budgets.show', compact('budget'));
    }

    /**
     * Show the form for editing the specified budget.
     */
    public function edit($id)
    {
        $budget = Budget::with('documents.user')->findOrFail($id);
        $users = User::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();
        $opportunities = Opportunity::orderBy('title')->get();

        return view('budgets.edit', compact('budget', 'users', 'clients', 'opportunities'));
    }

    /**
     * Update the specified budget in storage.
     */
    public function update(Request $request, $id)
    {
        $budget = Budget::findOrFail($id);

        $validated = $request->validate([
            'client_id'       => ['required', 'exists:clients,id'],
            'opportunity_id'  => ['nullable', 'exists:opportunities,id'],
            'tax_rate'        => ['nullable', 'numeric', 'min:0', 'max:100'],
            'subtotal'        => ['nullable', 'numeric', 'min:0'],
            'tax_amount'      => ['nullable', 'numeric', 'min:0'],
            'total'           => ['nullable', 'numeric', 'min:0'],
            'status'          => ['required', 'string', 'in:draft,sent,accepted,rejected,expired'],
            'valid_until'     => ['nullable', 'date'],
            'assigned_to'     => ['nullable', 'exists:users,id'],
            'description'     => ['nullable', 'string'],
            'notes'           => ['nullable', 'string'],
            'items'           => ['nullable', 'string'],
        ]);

        // Decode items JSON string
        if (!empty($validated['items'])) {
            $validated['items'] = json_decode($validated['items'], true);
        }

        $budget->update($validated);

        // Log activity
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Budget::class,
            'loggable_id'   => $budget->id,
            'type'          => 'updated',
            'description'   => "S'ha actualitzat el pressupost: {$budget->reference}",
        ]);

        return redirect()->back()
            ->with('success', 'Pressupost actualitzat correctament.');
    }

    /**
     * Remove the specified budget from storage.
     */
    public function destroy($id)
    {
        $budget = Budget::findOrFail($id);

        // Log activity before deletion
        Activity::create([
            'user_id'       => Auth::id(),
            'loggable_type' => Budget::class,
            'loggable_id'   => $budget->id,
            'type'          => 'deleted',
            'description'   => "S'ha eliminat el pressupost: {$budget->reference}",
        ]);

        $budget->delete();

        return redirect()->route('budgets.index')
            ->with('success', 'Pressupost eliminat correctament.');
    }
}
