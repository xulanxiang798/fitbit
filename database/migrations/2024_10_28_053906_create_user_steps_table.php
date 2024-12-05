<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_steps', function (Blueprint $table) {
            $table->id();
            $table->string('fitbit_user_id'); // Fitbit 用户 ID
            $table->date('date'); // 步数记录的日期
            $table->integer('steps'); // 记录的步数
            $table->timestamps();
            
            // 如果可能存在多个步数记录且可能有外键关系，推荐建立索引
            $table->index('fitbit_user_id');
        });

    Schema::create('user_heart_rates', function (Blueprint $table) {
        $table->id();
        $table->string('fitbit_user_id');
        $table->unsignedBigInteger('user_id');
        $table->dateTime('date_time');
        $table->integer('heart_rate');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    Schema::create('user_sleeps', function (Blueprint $table) {
        $table->id();
        $table->string('fitbit_user_id');
        $table->unsignedBigInteger('user_id');
        $table->date('date');
        $table->integer('sleep_score');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    Schema::create('user_calories', function (Blueprint $table) {
        $table->id();
        $table->string('fitbit_user_id');
        $table->unsignedBigInteger('user_id');
        $table->dateTime('date_time');
        $table->integer('calories');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });


    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_steps');
    }
}
