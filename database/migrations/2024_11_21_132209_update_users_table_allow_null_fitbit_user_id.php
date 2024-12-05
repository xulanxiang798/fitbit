<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fitbit_user_id')->nullable()->change(); // 设置 fitbit_user_id 允许 NULL
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('fitbit_user_id')->nullable(false)->change(); // 回滚更改
        });
    }
};
