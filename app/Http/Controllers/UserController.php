<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class UserController extends Controller
{
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return $user;
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

}
