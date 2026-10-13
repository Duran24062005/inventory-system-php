<?php

declare(strict_types=1);

use AlexiDg\InventorySystem\Controllers\HealthController;
use AlexiDg\InventorySystem\Controllers\HomeController;
use AlexiDg\InventorySystem\Http\Request;
use AlexiDg\InventorySystem\Http\Router;
use Dotenv\Dotenv;

$root = dirname(__DIR__);
$autoload = $root . '/vendor/autoload.php';

if (!is_file($autoload)) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Dependencies are not installed. Run composer install first.\n";
    exit(1);
}

require $autoload;

Dotenv::createImmutable($root)->safeLoad();

$router = new Router();
$router->get('/', [new HomeController(), 'index']);
$router->get('/health', [new HealthController(), 'index']);

$router->dispatch(Request::fromGlobals())->send();
