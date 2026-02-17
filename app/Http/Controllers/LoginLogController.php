<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginLogController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('logged_in_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('logged_in_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('logged_in_at', 'desc')->paginate(30)->withQueryString();

        $users = User::orderBy('name')->get();

        // Stats
        $totalLogins = LoginLog::where('status', 'success')->count();
        $failedLogins = LoginLog::where('status', 'failed')->count();
        $uniqueIps = LoginLog::distinct('ip_address')->count('ip_address');
        $todayLogins = LoginLog::where('status', 'success')
            ->whereDate('logged_in_at', today())
            ->count();

        // Logins per day for chart (last 30 days)
        $loginsPerDay = LoginLog::where('status', 'success')
            ->select(
                DB::raw("DATE_FORMAT(logged_in_at, '%Y-%m-%d') as day"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // Logins per hour distribution
        $loginsPerHour = LoginLog::where('status', 'success')
            ->select(
                DB::raw("HOUR(logged_in_at) as hour"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('login-logs.index', compact(
            'logs',
            'users',
            'totalLogins',
            'failedLogins',
            'uniqueIps',
            'todayLogins',
            'loginsPerDay',
            'loginsPerHour'
        ));
    }
}
