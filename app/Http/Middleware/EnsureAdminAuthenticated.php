<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAuthenticated
{
    /**
     * Handle an incoming request.
     * Ensures the authenticated user is an Admin model (not a regular User).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Must be authenticated via Sanctum
        if (! $request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Must be an Admin model instance
        if (! $request->user() instanceof \App\Models\Admin) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden. Admin access only.',
            ], 403);
        }

        // Must be active
        if (! $request->user()->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Account is deactivated.',
            ], 403);
        }

        // Optional role check: ->middleware('admin.auth:super_admin')
        if (! empty($roles) && ! in_array($request->user()->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient permissions.',
            ], 403);
        }

        return $next($request);
    }
}
