<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'qtd_person',
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
