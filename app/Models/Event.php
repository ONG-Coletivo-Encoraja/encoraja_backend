<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

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
}
