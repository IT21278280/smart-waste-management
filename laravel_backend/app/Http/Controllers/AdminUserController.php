<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users with filtering
     */
    public function index(Request $request)
    {
        $query = User::withCount('reports');

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('users', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
            'email_verified' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_admin' => $request->boolean('is_admin', false),
        ];

        if ($request->boolean('email_verified', false)) {
            $userData['email_verified_at'] = now();
        }

        User::create($userData);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $user->load(['reports' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(10);
        }]);

        $userStats = [
            'total_reports' => $user->reports()->count(),
            'pending_reports' => $user->reports()->where('status', 'pending')->count(),
            'resolved_reports' => $user->reports()->where('status', 'resolved')->count(),
            'recent_activity' => $user->reports()->orderBy('created_at', 'desc')->limit(5)->get()
        ];

        return response()->json([
            'user' => $user,
            'stats' => $userStats
        ]);
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'is_admin' => 'boolean',
            'email_verified' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_admin' => $request->boolean('is_admin', false),
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Handle email verification status
        if ($request->boolean('email_verified', false) && !$user->email_verified_at) {
            $userData['email_verified_at'] = now();
        } elseif (!$request->boolean('email_verified', false) && $user->email_verified_at) {
            $userData['email_verified_at'] = null;
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Update user status via AJAX
     */
    public function updateStatus(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:activate,deactivate,verify_email,unverify_email'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        switch ($request->action) {
            case 'activate':
                $user->update(['email_verified_at' => now()]);
                $message = 'User activated successfully';
                break;
            case 'deactivate':
                $user->update(['email_verified_at' => null]);
                $message = 'User deactivated successfully';
                break;
            case 'verify_email':
                $user->update(['email_verified_at' => now()]);
                $message = 'Email verified successfully';
                break;
            case 'unverify_email':
                $user->update(['email_verified_at' => null]);
                $message = 'Email verification removed';
                break;
        }

        return response()->json([
            'message' => $message,
            'user' => $user->fresh()
        ]);
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Check if user has reports
        $reportCount = $user->reports()->count();
        
        if ($reportCount > 0) {
            return redirect()->route('admin.users.index')
                ->with('error', "Cannot delete user. User has {$reportCount} associated reports. Please reassign or delete reports first.");
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Bulk operations on users
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'action' => 'required|in:activate,deactivate,delete'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $users = User::whereIn('id', $request->user_ids);
        $count = count($request->user_ids);

        switch ($request->action) {
            case 'activate':
                $users->update(['email_verified_at' => now()]);
                $message = "{$count} users activated successfully";
                break;
            case 'deactivate':
                $users->update(['email_verified_at' => null]);
                $message = "{$count} users deactivated successfully";
                break;
            case 'delete':
                // Check for users with reports
                $usersWithReports = $users->withCount('reports')->having('reports_count', '>', 0)->count();
                if ($usersWithReports > 0) {
                    return response()->json([
                        'error' => "Cannot delete {$usersWithReports} users as they have associated reports."
                    ], 422);
                }
                $users->delete();
                $message = "{$count} users deleted successfully";
                break;
        }

        return response()->json(['message' => $message]);
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = User::withCount('reports');

        // Apply same filters as index
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNull('email_verified_at');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Email Verified', 'Is Admin', 
                'Total Reports', 'Created At', 'Last Updated'
            ]);

            // CSV data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone ?? 'N/A',
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->is_admin ? 'Yes' : 'No',
                    $user->reports_count,
                    $user->created_at->format('Y-m-d H:i:s'),
                    $user->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get user statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'inactive_users' => User::whereNull('email_verified_at')->count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'recent_registrations' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'users_with_reports' => User::has('reports')->count()
        ];

        return response()->json($stats);
    }
}
