<?php

use App\App;
use App\Config;
use App\Container;
use App\Controllers\GeneratorExampleController;
use App\Controllers\HomeController;
use App\Controllers\InvoiceController;
use App\Controllers\UserController;
use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

const STORAGE_PATH = __DIR__ . '/../storage/';
const VIEW_PATH = __DIR__ . '/../views/';

$container = new Container();
$router = new Router($container);

try {
    $router->registerRoutesFromControllerAttributes(
        [
            HomeController::class,
            GeneratorExampleController::class,
            InvoiceController::class,
            UserController::class,
        ]
    );
} catch (ReflectionException $e) {
    header('HTTP/1.0 500 Internal Server Error');
}

new App(
    $container,
    $router,
    [
        'uri' => $_SERVER['REQUEST_URI'],
        'method' => $_SERVER['REQUEST_METHOD']
    ],
    new Config($_ENV)
)->run();
