<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_name',
        'column_name',
        'old_value',
        'new_value',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
