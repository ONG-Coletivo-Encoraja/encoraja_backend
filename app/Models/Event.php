<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'description', 
        'date', 
        'time', 
        'modality', 
        'status',           
        'type', 
        'target_audience', 
        'vacancies', 
        'social_vacancies', 
        'regular_vacancies',    
        'material', 
        'interest_area', 
        'price',
        'workload'
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    public function relatesEvents()
    {
        return $this->hasMany(RelatesEvent::class);
    }

    public function reviews()
    {
        return $this->hasMany(Reviews::class);
    }
}
