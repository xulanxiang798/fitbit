<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_heart_rate_zones', function (Blueprint $table) {
            $table->id();
            $table->string('fitbit_user_id');  // Fitbit 用户 ID
            $table->date('date');             // 数据日期
            $table->integer('out_of_range_minutes')->nullable(); // 超出范围的分钟数
            $table->integer('fat_burn_minutes')->nullable();     // 脂肪燃烧区的分钟数
            $table->integer('cardio_minutes')->nullable();       // 有氧区间的分钟数
            $table->integer('peak_minutes')->nullable();         // 峰值区间的分钟数
            $table->timestamps();              // 创建和更新时间
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_heart_rate_zones');
    }
};
