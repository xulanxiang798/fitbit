<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSleepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_sleeps', function (Blueprint $table) {
            $table->id();
            $table->string('fitbit_user_id');
            $table->date('date');
            $table->integer('total_minutes_asleep')->nullable();
            $table->integer('sleep_score')->nullable(); // 添加睡眠得分字段
            $table->time('bed_time')->nullable(); // 添加入睡时间字段
            $table->timestamps();
        });
        


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_sleeps');
    }
}
