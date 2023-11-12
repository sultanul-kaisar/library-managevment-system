<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'qty' => $this->qty,
            'is_active' => $this->is_active ? 1 : 0,
            'created_at' => $this->created_at,
        ];
    }
}
