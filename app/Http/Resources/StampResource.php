<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StampResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->premium_attribute) {
            $animalWithAttribute = $this->premium_attribute . " " . $this->animal;
        }
        else {
            $animalWithAttribute = $this->animal;
        }

        return 
        [
            'id' => $this->id,
            'stamp' => $animalWithAttribute

        ];
    }
}
