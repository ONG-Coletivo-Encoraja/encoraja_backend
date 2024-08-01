<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'ddd',
        'number',
        'country_number',
        'user_id'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
}
