<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAmusementRequest;
use App\Http\Requests\UpdateAmusementRequest;
use App\Models\Amusement;
use App\Http\Resources\AmusementResource;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Policies\AmusementPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class AmusementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $groupId = $request->query('group_id');
    
        $query = Amusement::query();
        
        // Only filter by group_id if it's provided
        if ($groupId) {
            $query->where('group_id', $groupId);
        }
        
        $amusements = $query->get();
        return AmusementResource::collection($amusements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmusementRequest $request)
    {
        // Validate the request using the StoreAmusementRequest
        $validatedData = $request->validated();
        $validatedData['group_id'] = $request->user()->group_id;
        $amusement = Amusement::create($validatedData);
        try {
            return response()->json([
                'message' => 'Amusement created successfully.',
                'data'    => new AmusementResource($amusement),
            ], 201);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Amusement not created',
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Amusement $amusement)
    {
        
        return response()->json(new AmusementResource($amusement));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmusementRequest $request, Amusement $amusement)
    {
        $user = Auth::user();
    
        // Check if the amusement belongs to the user's group
        if ($amusement->group_id !== $user->group_id) {
            return response()->json(['message' => 'You do not have permission to update this amusement.'], 403);
        }
        
        // The request is already validated through the UpdateAmusementRequest class
        $validated = $request->validated();
        
        // Update the amusement that was passed as a parameter
        $amusement->update($validated);
        
        return response()->json(['message' => 'Amusement updated successfully.', 'amusement' => new AmusementResource($amusement)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Amusement $amusement)
    {
        $amusement->delete();

        return response()->json([
            'message' => 'Amusement deleted',
        ], 204);
    }
}
