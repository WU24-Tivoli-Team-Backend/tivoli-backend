<?php

namespace App\Http\Middleware;

use App\Models\Group;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // First, check for API key
        $apiKey = $request->header('X-API-Key');
        
        if (!$apiKey) {
            return response()->json(['error' => 'API key is missing'], 401);
        }
        
        // Find the group by API key
        $group = Group::where('api_key', $apiKey)->first();
        
        if (!$group) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }
        
        // Add group to the request attributes for controllers
        $request->attributes->add(['group' => $group]);
        
        // Check for JWT token for user authentication
        $token = $request->bearerToken();
        
        if ($token) {
            try {
                // Decode the token and authenticate the user
                $payload = \Firebase\JWT\JWT::decode(
                    $token, 
                    new \Firebase\JWT\Key(env('JWT_SECRET'), 'HS256')
                );
                
                // Find the user
                $user = User::find($payload->sub);
                
                // Verify the user belongs to the authenticated group
                if ($user && $user->group_id == $group->id) {
                    Auth::login($user);
                    $request->attributes->add(['user' => $user]);
                }
            } catch (\Exception $e) {
                // Token is invalid, but we'll still proceed since API key is valid
                // Just won't set an authenticated user
            }
        }
        
        return $next($request);
    }
}