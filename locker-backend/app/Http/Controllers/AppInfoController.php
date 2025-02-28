<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class AppInfoController extends Controller
{
    /**
     * Returns information for identifying the application.
     */
    public function identify(): JsonResponse
    {

        return response()->json([
            'name' => 'Open-Locker',
            'type' => 'backend',
            'api_version' => 'v1',
            'identifier' => 'open-locker-backend',
            'environment' => app()->environment(),
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
