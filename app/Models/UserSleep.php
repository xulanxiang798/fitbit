<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSleep extends Model
{
    use HasFactory;

    protected $fillable = [
        'fitbit_user_id',
        'date',

        'start_time',             // 新增字段：睡眠开始时间
        'duration_minutes',       // 新增字段：睡眠持续时间（分钟）
            ];
}
