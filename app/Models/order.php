<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'car_id',
        'pick_up_loc',
        'drop_off_loc',
        'pick_up_date',
        'drop_off_date',
        'pick_up_time',
        'isActive',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function car()
    {
        return $this->belongsTo(car::class,'car_id','id');
    }
}
