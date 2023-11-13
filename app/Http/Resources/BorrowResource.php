<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowResource extends JsonResource
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
            'borrow_code' => $this->borrow_code,
            'user_name' => $this->user->name,
            'book_title' => $this->book->title,
            'status' => $this->status,
            'message' => $this->message,
            'qty' => $this->qty,
            'borrow_date' => $this->borrow_date,
            'return_date' => $this->return_date,
        ];
    }
}
