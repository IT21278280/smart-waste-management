<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function dashboard(): JsonResponse
    {
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'collected_reports' => Report::where('status', 'collected')->count(),
            'total_users' => User::count(),
            'reports_today' => Report::whereDate('created_at', today())->count(),
            'reports_this_week' => Report::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
        ];

        // Waste type distribution
        $wasteTypes = Report::select('predicted_label', DB::raw('count(*) as count'))
            ->whereNotNull('predicted_label')
            ->groupBy('predicted_label')
            ->get();

        // Recent activity
        $recentReports = Report::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return response()->json([
            'stats' => $stats,
            'waste_types' => $wasteTypes,
            'recent_reports' => $recentReports
        ]);
    }

    /**
     * Get all reports with filters
     */
    public function reports(Request $request): JsonResponse
    {
        $query = Report::with('user');

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('label')) {
            $query->where('predicted_label', $request->label);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $reports = $query->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 20));

        return response()->json($reports);
    }

    /**
     * Update report status
     */
    public function updateReportStatus(Request $request, Report $report): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,collected,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->notes
        ]);

        return response()->json([
            'message' => 'Report status updated successfully',
            'data' => $report->fresh()
        ]);
    }

    /**
     * Get users list
     */
    public function users(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount('reports')
                      ->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 20));

        return response()->json($users);
    }
}
