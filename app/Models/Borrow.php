<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrow extends Model
{
    use HasFactory, SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);    
    }

    public function book()
    {
        return $this->belongsTo(Book::class);    
    }

    public function borrowHistories()
    {
        return $this->hasMany(BorrowHistory::class);
    }
}
