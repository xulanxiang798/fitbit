<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHeartRateZone extends Model
{
    protected $fillable = [
        'fitbit_user_id',
        'date',
        'out_of_range_minutes',
        'fat_burn_minutes',
        'cardio_minutes',
        'peak_minutes',
    ];
}
