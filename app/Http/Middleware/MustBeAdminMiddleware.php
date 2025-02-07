<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustBeAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Use the correct guard (e.g., 'api' for JWT auth)
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'error' => 'Unauthorized. User not authenticated.'
            ], 401);
        }

        // You can now check if the user has the 'admin' role
        if ($user->role_id != 1) {
            return response()->json([
                'error' => 'Forbidden. You must be an admin.'
            ], 403);
        }

        return $next($request);
    }
}

