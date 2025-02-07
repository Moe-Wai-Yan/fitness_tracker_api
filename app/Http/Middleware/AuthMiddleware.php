<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check if the token exists in cookies
            $token = $request->cookie('token');

            if (!$token) {
                return response()->json([
                    'error' => 'Token not found in cookies.',
                ], 401); // Unauthorized
            }


            // Attempt to authenticate using the token
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) {
                return response()->json([
                    'error' => 'Invalid token or user not found.',
                ], 401); // Unauthorized
            }

            // Attach the user to the request
            $request->attributes->add(['user' => $user]);

            return $next($request);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'details' => $e->getMessage(),
            ], 500); // Internal Server Error
        }
    }
}
