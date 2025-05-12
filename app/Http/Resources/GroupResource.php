<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $members = $this->users->pluck('email')->toArray();
        $members = $this->users->map(function($user) {
            return $user->name;
        })->toArray();

        return [
            'uuid' => $this->id,
            'members' => $members,
        ];
    }
}
