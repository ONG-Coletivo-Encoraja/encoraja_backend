<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RelatesEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        // 'role',
        'event_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function reportAdmin()
    {
        return $this->hasOne(ReportAdmin::class);
    }
}
