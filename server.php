<?php
// Lightweight router for PHP's built-in webserver that defers to Laravel's server router.
// This file allows running: php -S 127.0.0.1:8000 -t public server.php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$requested = __DIR__ . '/public' . $uri;

// Serve the requested resource as-is if it exists in the public folder
if ($uri !== '/' && file_exists($requested)) {
    return false;
}

require_once __DIR__ . '/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php';
