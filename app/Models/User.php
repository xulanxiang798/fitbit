<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fitbit_user_id',
        'name',
        'email',
        'access_token',
        'refresh_token',
        'expires_in',
        'age',
        'gender',
        'height',
        'weight',
        'token_updated_at', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'token_updated_at' => 'datetime', // 确保 token_updated_at 自动转换为 Carbon 对象
    ];

    /**
     * 用户步数关联
     */
    public function steps()
    {
        return $this->hasMany(UserStep::class, 'fitbit_user_id', 'fitbit_user_id');
    }

    /**
     * 实时心率关联
     */
    public function realtimeHeartRates()
    {
        return $this->hasMany(UserRealtimeHeartRate::class, 'fitbit_user_id', 'fitbit_user_id');
    }
    
    public function heartRateZones()
    {
        return $this->hasMany(UserHeartRateZone::class, 'fitbit_user_id', 'fitbit_user_id');
    }
    
    /**
     * 睡眠数据关联
     */
    public function sleeps()
    {
        return $this->hasMany(UserSleep::class, 'fitbit_user_id', 'fitbit_user_id');
    }

    /**
     * 热量消耗数据关联
     */
    public function calories()
    {
        return $this->hasMany(UserCalorie::class, 'fitbit_user_id', 'fitbit_user_id');
    }

    /**
     * 获取用户头像
     */
    public function getAvatar()
    {
        // 如果用户有自定义头像，返回头像路径，否则返回默认头像
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : asset('fitbit_default_avatar.jpg');
    }

    public function isTokenExpiring(): bool
    {
        if (!$this->token_updated_at) {
            return true; // 如果 token 更新时间为空，假定需要刷新
        }

        $expiresAt = $this->token_updated_at->copy()->addSeconds($this->expires_in);
        return now()->greaterThanOrEqualTo($expiresAt->subHour());
    }
}
