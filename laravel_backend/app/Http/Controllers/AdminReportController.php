<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminReportController extends Controller
{
    /**
     * Display a listing of reports with filtering
     */
    public function index(Request $request)
    {
        $query = Report::with('user');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('waste_type')) {
            $query->where('waste_type', $request->waste_type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('reports', compact('reports'));
    }

    /**
     * Show the form for creating a new report
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('reports.create', compact('users'));
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'waste_type' => 'nullable|in:organic,plastic,metal,glass,hazardous',
            'status' => 'required|in:pending,in_progress,resolved',
            'confidence' => 'nullable|numeric|between:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['user_id', 'description', 'lat', 'lng', 'address', 'waste_type', 'status', 'confidence']);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reports', 'public');
            $data['image_path'] = $imagePath;
        }

        Report::create($data);

        return redirect()->route('admin.reports.index')->with('success', 'Report created successfully.');
    }

    /**
     * Display the specified report
     */
    public function show(Report $report)
    {
        $report->load('user');
        return response()->json($report);
    }

    /**
     * Show the form for editing the specified report
     */
    public function edit(Report $report)
    {
        $users = User::orderBy('name')->get();
        return view('reports.edit', compact('report', 'users'));
    }

    /**
     * Update the specified report
     */
    public function update(Request $request, Report $report)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string|max:1000',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'waste_type' => 'nullable|in:organic,plastic,metal,glass,hazardous',
            'status' => 'required|in:pending,in_progress,resolved',
            'confidence' => 'nullable|numeric|between:0,1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['user_id', 'description', 'lat', 'lng', 'address', 'waste_type', 'status', 'confidence']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($report->image_path) {
                Storage::disk('public')->delete($report->image_path);
            }
            
            $imagePath = $request->file('image')->store('reports', 'public');
            $data['image_path'] = $imagePath;
        }

        $report->update($data);

        return redirect()->route('admin.reports.index')->with('success', 'Report updated successfully.');
    }

    /**
     * Update report status via AJAX
     */
    public function updateStatus(Request $request, Report $report)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,resolved',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $report->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return response()->json([
            'message' => 'Report status updated successfully',
            'report' => $report
        ]);
    }

    /**
     * Remove the specified report
     */
    public function destroy(Report $report)
    {
        // Delete associated image if exists
        if ($report->image_path) {
            Storage::disk('public')->delete($report->image_path);
        }

        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Report deleted successfully.');
    }

    /**
     * Bulk delete reports
     */
    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_ids' => 'required|array',
            'report_ids.*' => 'exists:reports,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reports = Report::whereIn('id', $request->report_ids)->get();
        
        foreach ($reports as $report) {
            if ($report->image_path) {
                Storage::disk('public')->delete($report->image_path);
            }
            $report->delete();
        }

        return response()->json([
            'message' => count($request->report_ids) . ' reports deleted successfully'
        ]);
    }

    /**
     * Export reports to CSV
     */
    public function export(Request $request)
    {
        $query = Report::with('user');

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('waste_type')) {
            $query->where('waste_type', $request->waste_type);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        $filename = 'reports_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reports) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Description', 'Latitude', 'Longitude', 
                'Address', 'Waste Type', 'Status', 'Confidence', 'Created At'
            ]);

            // CSV data
            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->id,
                    $report->user->name ?? 'N/A',
                    $report->user->email ?? 'N/A',
                    $report->description,
                    $report->lat,
                    $report->lng,
                    $report->address,
                    $report->waste_type,
                    $report->status,
                    $report->confidence,
                    $report->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
