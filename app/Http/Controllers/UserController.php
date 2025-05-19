<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserStampResource;
use App\Models\User;
use Illuminate\Http\Request;


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
        try {
            $user->load('stamps');

            return new UserStampResource($user);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->update($request->validated());

            return (new UserResource($user))
                ->additional(['message' => 'User info updated successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // PATCH /api/user
    public function updateSelf(UpdateUserRequest $request)
    {
        try {
            $user = $request->user();
            $user->update($request->validated());

            return (new UserResource($user))
                ->additional(['message' => 'Profile updated successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update your profile.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully.',
            ], 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }




    // DELETE /api/user (if we want to allow users to delete their own account)
    public function destroySelf(Request $request)
    {
        try {
            $user = $request->user();
            $user->delete();

            return response()->noContent(); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete your account.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
