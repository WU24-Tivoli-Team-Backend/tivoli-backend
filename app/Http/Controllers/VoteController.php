<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voteCounts = DB::table('votes')
        ->join('amusements', 'votes.amusement_id', '=', 'amusements.id')
        ->select('amusements.name as amusement', DB::raw('count(*) as votes'))
        ->groupBy('amusements.id', 'amusements.name')
        ->get();

        return $voteCounts->toArray();
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
            'data'    => $vote,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        $vote = Vote::findOrFail($vote->id);

        return $vote;
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
        $vote->update($request->validated());

        return response()->json([
            'message' => 'Your vote has been updated successfully!',
            'data'    => $vote,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
