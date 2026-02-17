<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Client;
use App\Models\Opportunity;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLeads = Lead::count();
        $activeClients = Client::count();
        $openOpportunities = Opportunity::whereNotIn('stage', ['closed_won', 'closed_lost'])->count();
        $revenuePipeline = Opportunity::where('stage', '!=', 'closed_lost')->sum('value');

        $stageOrder = ['prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];

        $stageData = Opportunity::select('stage', DB::raw('COUNT(*) as count'), DB::raw('SUM(value) as total_value'))
            ->groupBy('stage')
            ->get()
            ->sortBy(function ($item) use ($stageOrder) {
                return array_search($item->stage, $stageOrder);
            })->values();

        $monthlyRevenue = Opportunity::where('stage', 'closed_won')
            ->select(
                DB::raw("strftime('%Y-%m', expected_close_date) as month"),
                DB::raw('SUM(value) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $recentActivities = Activity::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $topOpportunities = Opportunity::with(['client', 'assignedTo'])
            ->where('stage', '!=', 'closed_lost')
            ->orderBy('value', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalLeads',
            'activeClients',
            'openOpportunities',
            'revenuePipeline',
            'stageData',
            'monthlyRevenue',
            'recentActivities',
            'topOpportunities'
        ));
    }
}
