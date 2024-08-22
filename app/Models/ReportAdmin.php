<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportAdmin extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'qtt_person',
        'description',
        'results',
        'observation',
        'relates_event_id'
    ];

    public function relatesEvent()
    {
        return $this->belongsTo(RelatesEvent::class);
    }
}
