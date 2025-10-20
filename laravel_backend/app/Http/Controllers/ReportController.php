<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
      /**
     * Store a newly created report in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string|max:500',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Store the uploaded image
            $imagePath = $request->file('image')->store('reports', 'public');
            
            // Create the report record
            $report = Report::create([
                'user_id' => auth()->id(),
                'image_path' => $imagePath,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'description' => $request->description,
                'address' => $request->address,
                'status' => 'pending'
            ]);

            // Call ML service for prediction
            $this->predictWasteType($report);

            return response()->json([
                'message' => 'Report submitted successfully',
                'data' => $report->fresh()
            ], 201);

        } catch (\Exception $e) {
            Log::error('Report creation failed: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to create report',
                'error' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified report.
     */
    public function show(Report $report): JsonResponse
    {
        // Check if user can view this report
        if ($report->user_id !== auth()->id() && !auth()->user()->is_admin) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'data' => $report->load('user')
        ]);
    }

    /**
     * Display a listing of reports.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Report::query();

        // Filter by user if not admin
        if (!auth()->user()->is_admin ?? false) {
            $query->byUser(auth()->id());
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->has('label')) {
            $query->byLabel($request->label);
        }

        if ($request->has('recent_days')) {
            $query->recent($request->recent_days);
        }

        $reports = $query->with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 15));

        return response()->json($reports);
    }

    /**
     * Update the specified report status.
     */
    public function updateStatus(Request $request, Report $report): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,assigned,collected,rejected'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Only admin can update status
        if (!auth()->user()->is_admin ?? false) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $report->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Report status updated successfully',
            'data' => $report
        ]);
    }

    /**
     * Call ML service to predict waste type
     */
    private function predictWasteType(Report $report): void
    {
        try {
            $mlServiceUrl = config('services.ml.url') . '/predict';
            $imagePath = Storage::disk('public')->path($report->image_path);

            if (!file_exists($imagePath)) {
                Log::error('Image file not found: ' . $imagePath);
                return;
            }

            $response = Http::timeout(30)
                ->attach('file', file_get_contents($imagePath), basename($imagePath))
                ->post($mlServiceUrl);

            if ($response->successful()) {
                $data = $response->json();
                
                $report->update([
                    'predicted_label' => $data['label'] ?? null,
                    'confidence' => $data['confidence'] ?? null
                ]);

                Log::info("ML prediction successful for report {$report->id}: {$data['label']} ({$data['confidence']})");
            } else {
                Log::error("ML service failed for report {$report->id}: " . $response->body());
            }

        } catch (\Exception $e) {
            Log::error("ML prediction error for report {$report->id}: " . $e->getMessage());
        }
    }
}
