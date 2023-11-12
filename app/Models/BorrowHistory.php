<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowHistory extends Model
{
    use HasFactory;

    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }
}
