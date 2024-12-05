<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class TaskSchedulerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 将定时任务的相关代码删除
        // 例如：
        // $schedule->command('fitbit:update-steps')->everyFiveMinutes();
    }
}
