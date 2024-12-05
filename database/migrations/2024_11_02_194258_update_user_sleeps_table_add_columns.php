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
    Schema::table('user_sleeps', function (Blueprint $table) {
        if (!Schema::hasColumn('user_sleeps', 'minutes_to_fall_asleep')) {
            $table->integer('minutes_to_fall_asleep')->nullable();
        }
        if (!Schema::hasColumn('user_sleeps', 'start_time')) {
            $table->time('start_time')->nullable();
        }
        if (!Schema::hasColumn('user_sleeps', 'duration_minutes')) {
            $table->integer('duration_minutes')->nullable();
        }
    });
}

public function down()
{
    Schema::table('user_sleeps', function (Blueprint $table) {
        $table->dropColumn(['minutes_to_fall_asleep', 'start_time', 'duration_minutes']);
    });
}


};
