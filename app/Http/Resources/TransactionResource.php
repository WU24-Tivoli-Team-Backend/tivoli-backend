<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'group' => $this->group_id,
            'user' => $this->user_id,
            'amusement' => $this->amusement_id,
            'stake' => $this->stake_amount,
            'payout' => $this->payout_amount,
            'stamp' => $this->stamp_id,
        ];
    }
}
