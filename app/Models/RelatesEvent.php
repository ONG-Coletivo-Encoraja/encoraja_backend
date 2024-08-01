<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatesEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'role',
        'event_id',
        'user_id'
    ];
}
