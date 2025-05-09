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
            'buyer' => $this->user_id, // Renamed user_id to seller
            'seller' => $this->group_id,
            'amount' => $this->stake_amount,
            'amount' => $this->payout_amount,
            // Add any other fields you need
        ];
    }
}
