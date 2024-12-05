<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_realtime_heart_rates', function (Blueprint $table) {
            $table->id();
            $table->string('fitbit_user_id');  // Fitbit 用户 ID
            $table->datetime('timestamp');     // 数据时间戳
            $table->integer('heart_rate');     // 实时心率值
            $table->timestamps();              // 创建和更新时间
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_realtime_heart_rates');
    }
};
