<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MLService
{
    private $baseUrl;
    private $timeout;

    public function __construct()
    {
        $this->baseUrl = env('ML_SERVICE_URL', 'http://localhost:8001');
        $this->timeout = 30; // 30 seconds timeout
    }

    /**
     * Check if ML service is available
     */
    public function isAvailable(): bool
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/health");
            return $response->successful() && $response->json('status') === 'healthy';
        } catch (\Exception $e) {
            Log::error('ML Service health check failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get ML service status
     */
    public function getStatus(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/health");
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return [
                'status' => 'error',
                'message' => 'Service unavailable'
            ];
        } catch (\Exception $e) {
            Log::error('ML Service status check failed: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Predict waste type from uploaded file
     */
    public function predictWasteType(UploadedFile $file): array
    {
        try {
            // Validate file
            if (!$file->isValid()) {
                throw new \Exception('Invalid file upload');
            }

            // Check file type
            if (!str_starts_with($file->getMimeType(), 'image/')) {
                throw new \Exception('File must be an image');
            }

            // Check file size (max 5MB)
            if ($file->getSize() > 5 * 1024 * 1024) {
                throw new \Exception('File size too large (max 5MB)');
            }

            // Make prediction request
            $response = Http::timeout($this->timeout)
                ->attach('file', file_get_contents($file->path()), $file->getClientOriginalName())
                ->post("{$this->baseUrl}/predict");

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('ML Prediction successful', [
                    'file' => $file->getClientOriginalName(),
                    'predicted_label' => $result['label'],
                    'confidence' => $result['confidence']
                ]);

                return [
                    'success' => true,
                    'data' => $result
                ];
            }

            throw new \Exception('Prediction request failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('ML Prediction failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName() ?? 'unknown'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Predict waste types for multiple files
     */
    public function batchPredict(array $files): array
    {
        try {
            if (count($files) > 10) {
                throw new \Exception('Maximum 10 files allowed per batch');
            }

            $attachments = [];
            foreach ($files as $index => $file) {
                if (!$file->isValid() || !str_starts_with($file->getMimeType(), 'image/')) {
                    continue;
                }
                
                $attachments["files[{$index}]"] = [
                    'contents' => file_get_contents($file->path()),
                    'filename' => $file->getClientOriginalName()
                ];
            }

            if (empty($attachments)) {
                throw new \Exception('No valid image files provided');
            }

            $response = Http::timeout($this->timeout * 2) // Double timeout for batch
                ->attach($attachments)
                ->post("{$this->baseUrl}/batch_predict");

            if ($response->successful()) {
                $result = $response->json();
                
                Log::info('ML Batch prediction successful', [
                    'files_count' => count($files),
                    'results_count' => count($result['results'] ?? [])
                ]);

                return [
                    'success' => true,
                    'data' => $result
                ];
            }

            throw new \Exception('Batch prediction request failed: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('ML Batch prediction failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get available waste categories
     */
    public function getWasteCategories(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/");
            
            if ($response->successful()) {
                return $response->json('classes', []);
            }
            
            // Fallback categories
            return ['Organic', 'Plastic', 'Metal', 'Glass', 'Hazardous'];
            
        } catch (\Exception $e) {
            Log::error('Failed to get waste categories: ' . $e->getMessage());
            return ['Organic', 'Plastic', 'Metal', 'Glass', 'Hazardous'];
        }
    }

    /**
     * Format confidence score for display
     */
    public static function formatConfidence(float $confidence): string
    {
        return number_format($confidence * 100, 1) . '%';
    }

    /**
     * Determine if prediction is uncertain
     */
    public static function isUncertain(float $confidence, float $threshold = 0.6): bool
    {
        return $confidence < $threshold;
    }

    /**
     * Get confidence level description
     */
    public static function getConfidenceLevel(float $confidence): string
    {
        if ($confidence >= 0.8) {
            return 'High';
        } elseif ($confidence >= 0.6) {
            return 'Medium';
        } elseif ($confidence >= 0.4) {
            return 'Low';
        } else {
            return 'Very Low';
        }
    }

    /**
     * Get recommendation based on confidence
     */
    public static function getRecommendation(float $confidence): string
    {
        if ($confidence >= 0.8) {
            return 'Classification is highly confident';
        } elseif ($confidence >= 0.6) {
            return 'Classification is reasonably confident';
        } elseif ($confidence >= 0.4) {
            return 'Consider retaking photo for better accuracy';
        } else {
            return 'Please retake photo with better lighting and focus';
        }
    }
}
