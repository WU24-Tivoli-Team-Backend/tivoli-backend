<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Firebase\JWT\JWT;
use App\Models\User;

class JwtTokenController extends Controller
{
    public function __invoke(Request $request)
    {
        // The API key middleware has already authenticated the group
        $group = $request->attributes->get('group');
        
        // Get the user from the request
        $userId = $request->input('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'User ID is required'], 400);
        }
        
        $user = User::find($userId);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // Ensure the user belongs to the authenticated group
        if ($user->group_id != $group->id) {
            return response()->json(['error' => 'User does not belong to the authenticated group'], 403);
        }

        $payload = [
            'iss' => 'yrgobanken.vip',
            'sub' => $user->id,
            'email' => $user->email,
            'group_id' => $user->group_id,
            'iat' => time(),
            'exp' => time() + (60 * 60), // 1 hour
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json(['token' => $jwt]);
    }
}