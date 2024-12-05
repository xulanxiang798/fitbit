<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRealtimeHeartRate extends Model
{
    protected $fillable = ['fitbit_user_id', 'timestamp', 'heart_rate'];
}
