<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name ,
            'email' => $this->email,
            'role' => $this->role,
            "is_active" => $this->is_active ? 1 : 0,
        ];
    }
}
