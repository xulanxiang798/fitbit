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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); 
            $table->string('fitbit_user_id')->unique(); 
            $table->string('name')->nullable(); 
            $table->string('email')->nullable();
            $table->string('access_token');
            $table->string('refresh_token')->nullable();
            $table->integer('expires_in')->nullable();
            $table->integer('age')->nullable(); // 新增年龄
            $table->string('gender')->nullable(); // 新增性别
            $table->float('height')->nullable(); // 新增身高
            $table->float('weight')->nullable(); // 新增体重
            $table->timestamps();
        });
    }
    


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
