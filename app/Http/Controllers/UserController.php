<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return UserResource::collection($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }


    // public function show($id)
    // {

    //     try {
    //         $user = User::findOrFail($id);
    //         return $user;
    //     } catch (ModelNotFoundException $exception) {
    //         return response()->json([
    //             'message' => 'User not found',
    //         ], 404);
    //     }
    // }
}
