<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
     /* Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        // Change this as needed, to only return the fields we want to include
        // Make sure only admin can retrieve this
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'group_id'  => $this->group_id,
            'balance'   => $this->balance,
            'image_url' => $this->image_url,
            'github'    => $this->github,
            'url'       => $this->url,
        ];
    }
}
