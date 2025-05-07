<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['status' => false, 'message' => 'Token not provided'], 401);
        }

        try {
            $decoded = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $request->user = $decoded;  // Store user data in the request object
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Invalid token'], 401);
        }

        return $next($request);
    }
}
