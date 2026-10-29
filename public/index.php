<?php
/**
 * FRONT CONTROLLER - satu-satunya pintu masuk aplikasi.
 */
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');

// Autoload sederhana: App\Core\Router -> app/Core/Router.php
spl_autoload_register(function (string $class): void {
    if (strpos($class, 'App\\') !== 0) return;
    $file = APP_PATH . '/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    if (is_file($file)) require $file;
});

require APP_PATH . '/Core/helpers.php';

$config = require ROOT_PATH . '/config/config.php';
App\Core\Config::load($config);

ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/Jakarta');

session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();

$router = new App\Core\Router();
require APP_PATH . '/routes.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    App\Core\Request::path()
);
