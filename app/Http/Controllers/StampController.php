<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStampRequest;
use App\Http\Requests\UpdateStampRequest;
use App\Models\Stamp;
use App\Http\Resources\StampResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StampController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stamps = Stamp::all();

        if ($stamps->isEmpty()) {
            return response()->json([
                'message' => 'No stamps available.',
            ], 404);
        }

        return StampResource::collection($stamps);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $stamp = Stamp::findOrFail($id);

            return new StampResource($stamp);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'message' => 'Stamp not found',
            ], 404);
        }
    }
}
