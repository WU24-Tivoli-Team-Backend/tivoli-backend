<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AmusementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'url' => $this->url,
            // Relationships
            'group' => $this->whenLoaded('group'), // Include group data if loaded
            'stamps' => $this->whenLoaded('stamps'), // Include stamps if loaded

        ];
    }
}
