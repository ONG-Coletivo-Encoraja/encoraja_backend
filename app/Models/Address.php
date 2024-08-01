<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    protected $fillable = [
        'street',
        'number',
        'neighbourhood',
        'city',
        'zip_code',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
