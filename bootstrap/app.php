<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 这里可以添加任何你需要的中间件
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 这里可以处理应用的异常
    })
    ->withProviders([
        // 这里只保留必要的服务提供者
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        // 如果有其他你自己添加的服务提供者，可以在此列出
    ])
    ->create();
