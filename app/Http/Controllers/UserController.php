<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return (new UserResource($user))
            ->additional(['message' => 'User info updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Amusement deleted',
        ], 204);
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
