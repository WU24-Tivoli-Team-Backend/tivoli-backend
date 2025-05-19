<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class UserStampResource extends JsonResource
{

    protected $stamps;

    public function __construct($resource, $stamps = null)
    {
        parent::__construct($resource);
        $this->stamps = $stamps;
    }
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
           Log::info('User ID: ' . $this->id);
    Log::info('Stamps loaded?: ' . ($this->relationLoaded('stamps') ? 'Yes' : 'No'));
    Log::info('Stamps count: ' . $this->stamps->count());
    Log::info('Stamps data: ' . $this->stamps->toJson());
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'group_id'  => $this->group_id,
            'balance'   => $this->balance,
            'image_url' => $this->image_url,
            'github'    => $this->github,
            'url'       => $this->url,
            'stamps' => $this->whenLoaded('stamps', function () {
                return $this->stamps;
            }),
        ];
    }
}
