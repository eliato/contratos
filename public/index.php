<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ruta a la raiz de Laravel (fuera de public_html, en ~/contratosaas/)
define('LARAVEL_ROOT', __DIR__.'/../contratosaas');

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = LARAVEL_ROOT.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require LARAVEL_ROOT.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once LARAVEL_ROOT.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
