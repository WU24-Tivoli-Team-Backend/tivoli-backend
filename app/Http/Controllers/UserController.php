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

    // PATCH /api/user
    public function updateSelf(UpdateUserRequest $request)
    {
        $user = $request->user();
        $user->update($request->validated());

        return (new UserResource($user))
            ->additional(['message' => 'Profile updated successfully.']);
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




    // DELETE /api/user (if we want to allow users to delete their own account)
    public function destroySelf(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->noContent(); // 204 No Content
    }
}
