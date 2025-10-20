<?php

namespace App\Http\Controllers;

use App\Services\MLService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MLController extends Controller
{
    protected $mlService;

    public function __construct(MLService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Get ML service status
     */
    public function status(): JsonResponse
    {
        $status = $this->mlService->getStatus();
        $isAvailable = $this->mlService->isAvailable();

        return response()->json([
            'ml_service' => $status,
            'available' => $isAvailable,
            'categories' => $this->mlService->getWasteCategories()
        ]);
    }

    /**
     * Predict waste type from uploaded image
     */
    public function predict(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$this->mlService->isAvailable()) {
            return response()->json([
                'success' => false,
                'error' => 'ML service is currently unavailable'
            ], 503);
        }

        $result = $this->mlService->predictWasteType($request->file('image'));

        if ($result['success']) {
            $data = $result['data'];
            
            return response()->json([
                'success' => true,
                'prediction' => [
                    'label' => $data['label'],
                    'confidence' => $data['confidence'],
                    'confidence_formatted' => MLService::formatConfidence($data['confidence']),
                    'confidence_level' => MLService::getConfidenceLevel($data['confidence']),
                    'is_uncertain' => $data['is_uncertain'],
                    'recommendation' => MLService::getRecommendation($data['confidence']),
                    'all_predictions' => $data['all_predictions']
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error']
        ], 500);
    }

    /**
     * Batch predict waste types
     */
    public function batchPredict(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$this->mlService->isAvailable()) {
            return response()->json([
                'success' => false,
                'error' => 'ML service is currently unavailable'
            ], 503);
        }

        $result = $this->mlService->batchPredict($request->file('images'));

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'results' => $result['data']
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error']
        ], 500);
    }

    /**
     * Get available waste categories
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'categories' => $this->mlService->getWasteCategories()
        ]);
    }
}
