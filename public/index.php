<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    // 使用 Kernel 类来处理 HTTP 请求
    $kernel = $app->make(Kernel::class);

    $response = $kernel->handle(
        $request = Request::capture()
    );

    $response->send();

    $kernel->terminate($request, $response);
} catch (\Exception $e) {
    // 捕获异常并记录到日志中
    \Log::error('An error occurred: ' . $e->getMessage());
    \Log::error('Trace: ' . $e->getTraceAsString());

    // 返回一个简单的错误页面
    http_response_code(500);
    echo 'An error occurred. Please check the logs for more details.';
}
