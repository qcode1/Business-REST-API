<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

use function Pest\Laravel\swap;

class VerifyApiHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check for headers
        if (!$request->hasHeader('X-API-KEY') || !$request->hasHeader('X-APP-ID')) {

            $message = 'Missing required header(s): ' .
                (!$request->hasHeader('X-API-KEY') && !$request->hasHeader('X-APP-ID')
                    ? 'X-API-KEY and X-APP-ID'
                    : (!$request->hasHeader('X-API-KEY')
                        ? 'X-API-KEY'
                        : 'X-APP-ID'));

            return response()->json([
                'status' => 403,
                'message' => $message
            ], status: 403);
        }

        // Get authentication header values
        $apiKey = $request->header('X-API-KEY');
        $appId = $request->header('X-APP-ID');

        // Get configuration source
        $source = config('app.app_auth_source', 'environment');
        $app_mode = config('app.app_mode', 'dev');

        // Validation logic
        if (!$this->isValidApiKey($source, $app_mode, $apiKey, $appId)) {
            return response()->json([
                'error' => 'Invalid API credentials',
                'headers' => $request->headers->all(),
                'source' => $source,
                'app_mode' => $app_mode,
            ], 401);
        }

        // If the API key is valid, proceed with the request
        return $next($request);
    }


    protected function isValidApiKey($source, $app_mode, $apiKey, $appId)
    {
        // Check if the source is valid
        switch ($source) {

            case 'environment':
                // Validate against environment variables
                return $apiKey === config('app.api_key') && $appId === config('app.app_id');

            case 'config':
                $client = "rest-api";
                $apiKeyService = new \App\ApiKeyService();
                $apiKeyValid = $apiKeyService->validateKeys($client, $app_mode, $apiKey);
                $appIdValid = $apiKeyService->validateAppId($client, $appId);

                // Check if both API key and APP ID are valid
                return $apiKeyValid && $appIdValid;

            default:
                return false;
        }
    }
}
