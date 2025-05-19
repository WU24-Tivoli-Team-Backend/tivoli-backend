<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoteRequest;
use App\Http\Requests\UpdateVoteRequest;
use App\Models\Vote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $voteCounts = DB::table('votes')
                ->leftJoin('votes', 'votes.amusement_id', '=', 'amusements.id')
                ->select('amusements.name as amusement', DB::raw('count(*) as votes'))
                ->groupBy('amusements.id', 'amusements.name')
                ->get();

            return response()->json($voteCounts, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch vote counts',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        Log::info('Store method triggered');
        try {
            Log::info('Request data:', $request->all());

            $vote = Vote::create($request->validated());

            return response()->json([
                'message' => 'You have submitted your vote!',
                'data'    => $vote,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error submitting vote:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'Failed to submit vote',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        try {
            $foundVote = Vote::findOrFail($vote->id);

            return response()->json($foundVote, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Vote not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve vote',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        try {
            $vote->update($request->validated());

            return response()->json([
                'message' => 'Your vote has been updated successfully!',
                'data'    => $vote,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update vote',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
