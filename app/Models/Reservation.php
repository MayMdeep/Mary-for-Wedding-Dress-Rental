<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function dress()
    {
        return $this->belongsTo(Dress::class, 'dress_id', 'id');
    }
}
