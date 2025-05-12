<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Vote;
use App\Http\Resources\VoteResource;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $votes = Vote::all();

        return VoteResource::collection($votes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoteRequest $request)
    {
        $vote = Vote::create($request->validated());

        return response()->json([
            'message' => 'You have submitted your vote!',
            'data'    => new VoteResource($vote),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        $vote = Vote::findOrFail($vote->id);

        return new VoteResource($vote);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoteRequest $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
