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
            'stamps' => $this->whenLoaded('stamps'),
            'stamp_id' => $this->stamp_id,
        ];
    }
}
