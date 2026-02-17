<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities with optional filters.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['user', 'loggable']);

        // Filter by activity type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(25)->withQueryString();

        $users = User::orderBy('name')->get();

        return view('activities.index', compact('activities', 'users'));
    }
}
