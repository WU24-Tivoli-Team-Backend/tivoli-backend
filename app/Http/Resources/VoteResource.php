<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class VoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $voteCounts = DB::table('votes')
            ->join('amusements', 'votes.amusement_id', '=', 'amusements.id')
            ->select('amusements.name as amusement', DB::raw('count(*) as votes'))
            ->groupBy('amusements.id', 'amusements.name')
            ->get();

        return $voteCounts->toArray();
    }
}
