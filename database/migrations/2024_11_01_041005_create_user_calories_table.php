<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_calories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // 用户表的外键
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('date')->index(); // 增加索引以优化日期查询
            $table->integer('calories')->nullable(); // 每日消耗热量
            $table->timestamps(); // 包括 created_at 和 updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_calories');
    }
};
