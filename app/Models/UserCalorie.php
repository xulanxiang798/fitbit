<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCalorie extends Model
{
    use HasFactory;

    // 表名（如果不一致，需指定）
    protected $table = 'user_calories';

    // 批量赋值的字段
    protected $fillable = [
        'fitbit_user_id',
        'date',
        'calories',
    ];

    // 告诉 Laravel 哪些字段是日期类型
    protected $dates = ['date'];

    // 隐藏的字段（可选）
    protected $hidden = ['created_at', 'updated_at'];

    // 与用户模型的关系
    public function user()
    {
        return $this->belongsTo(User::class, 'fitbit_user_id', 'fitbit_user_id');
    }

    // 启动模型时默认值处理
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->calories = $model->calories ?? 0; // 默认值
        });
    }
}
