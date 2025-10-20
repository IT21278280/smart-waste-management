<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $stats = [
            'users' => User::count(),
            'reports' => Report::count(),
            'pending' => Report::where('status', 'pending')->count(),
            'resolved' => Report::where('status', 'resolved')->count(),
        ];

        // Get recent reports
        $recentReports = Report::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentReports'));
    }

    public function reports()
    {
        $reports = Report::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('reports', compact('reports'));
    }

    public function users()
    {
        $users = User::withCount('reports')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('users', compact('users'));
    }

    public function analytics()
    {
        try {
            $wasteTypes = Report::selectRaw('waste_type, COUNT(*) as count')
                ->groupBy('waste_type')
                ->get();

            $monthlyReports = Report::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->get();

            // Get additional statistics
            $totalReports = Report::count();
            $resolvedReports = Report::where('status', 'resolved')->count();
            $pendingReports = Report::where('status', 'pending')->count();
            $inProgressReports = Report::where('status', 'in_progress')->count();

            return view('analytics', compact('wasteTypes', 'monthlyReports', 'totalReports', 'resolvedReports', 'pendingReports', 'inProgressReports'));
        } catch (\Exception $e) {
            // Fallback data if database queries fail
            $wasteTypes = collect();
            $monthlyReports = collect();
            $totalReports = 0;
            $resolvedReports = 0;
            $pendingReports = 0;
            $inProgressReports = 0;

            return view('analytics', compact('wasteTypes', 'monthlyReports', 'totalReports', 'resolvedReports', 'pendingReports', 'inProgressReports'))
                ->with('error', 'Unable to load analytics data: ' . $e->getMessage());
        }
    }
}
