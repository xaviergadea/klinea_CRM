<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $stageOrder = ['prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];

        $pipelineData = Opportunity::select(
                'stage',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(value) as total')
            )
            ->groupBy('stage')
            ->get()
            ->sortBy(function ($item) use ($stageOrder) {
                return array_search($item->stage, $stageOrder);
            })->values();

        $totalLeads = Lead::count();
        $wonLeads = Lead::where('status', 'won')->count();
        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;

        $monthlyRevenue = Opportunity::where('stage', 'closed_won')
            ->select(
                DB::raw("strftime('%Y-%m', expected_close_date) as month"),
                DB::raw('SUM(value) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topPerformers = User::select(
                'users.id',
                'users.name',
                DB::raw('COUNT(opportunities.id) as won_count'),
                DB::raw('COALESCE(SUM(opportunities.value), 0) as total_value')
            )
            ->leftJoin('opportunities', function ($join) {
                $join->on('users.id', '=', 'opportunities.assigned_to')
                     ->where('opportunities.stage', '=', 'closed_won');
            })
            ->where('users.role', '!=', 'admin')
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_value', 'desc')
            ->limit(5)
            ->get();

        $wins = Opportunity::where('stage', 'closed_won')->count();
        $losses = Opportunity::where('stage', 'closed_lost')->count();
        $winLossRatio = [
            'wins' => $wins,
            'losses' => $losses,
            'ratio' => $losses > 0 ? round($wins / $losses, 2) : $wins,
        ];

        return view('reports.index', compact(
            'pipelineData',
            'conversionRate',
            'monthlyRevenue',
            'topPerformers',
            'winLossRatio'
        ));
    }
}
