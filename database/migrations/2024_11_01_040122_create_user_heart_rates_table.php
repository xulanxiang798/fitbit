<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserHeartRatesTable extends Migration
{
    /**
     * 运行迁移
     */
    public function up()
    {
        Schema::create('user_heart_rates', function (Blueprint $table) {
            $table->id();
            $table->string('fitbit_user_id'); // Fitbit 用户 ID
            $table->unsignedBigInteger('user_id'); // 用户 ID
            $table->dateTime('date_time'); // 数据记录时间
            $table->integer('heart_rate'); // 心率
            $table->timestamps();

            // 设置外键约束
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * 回滚迁移
     */
    public function down()
    {
        Schema::dropIfExists('user_heart_rates');
    }
}
