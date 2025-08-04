<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenValideted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Token');

        if(empty($token)) {
            return response()->json([
                'success' => false,
                'message' => "Token not provided"
            ]);
        }

        if($token != env('APP_KEY')) {
            return response()->json([
                'success' => false,
                'message' => "Token not provided"
            ]);
        }

        return $next($request);
    }
}
