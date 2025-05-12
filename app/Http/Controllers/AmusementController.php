<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAmusementRequest;
use App\Http\Requests\UpdateAmusementRequest;
use App\Models\Amusement;
use App\Http\Resources\AmusementResource;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;

class AmusementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $amusements = Amusement::all();
        return AmusementResource::collection($amusements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAmusementRequest $request)
    {
        // Validate the request using the StoreAmusementRequest
        $amusement = Amusement::create($request->validated());

        return response()->json([
            'message' => 'Amusement created successfully.',
            'data'    => new AmusementResource($amusement),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Amusement $amusement)
    {
        return new AmusementResource($amusement);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAmusementRequest $request, Amusement $amusement)
    {
        $amusement->update($request->validated());

        return (new AmusementResource($amusement))
            ->additional(['message' => 'Amusement updated successfully.']);
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
