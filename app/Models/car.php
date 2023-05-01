<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class car extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'car_type',
        'rating',
        'fuel',
        'image',
        'hour_rate',
        'day_rate',
        'month_rate',
        'isActive',
    ];
}
