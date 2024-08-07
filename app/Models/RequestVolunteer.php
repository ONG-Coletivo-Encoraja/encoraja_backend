<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestVolunteer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status',
        'availability',
        'course_experience',
        'how_know',
        'expectations',
    ];


    public function user()
    {
        return $this->hasOne(User::class);
    }
}
