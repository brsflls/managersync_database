<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Request:', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'body' => $request->all(),
        ]);

        $response = $next($request);

        Log::info('Response:', [
            'status' => $response->status(),
        ]);

        return $response;
    }
}
