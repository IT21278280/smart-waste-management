<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?? $request->query('api_key');
        $validApiKey = config('services.ml.api_key');

        if (!$apiKey || $apiKey !== $validApiKey) {
            return response()->json([
                'message' => 'Invalid or missing API key'
            ], 401);
        }

        return $next($request);
    }
}
