<?php declare(strict_types=1);

require_once '../vendor/autoload.php';

use League\Container\Container;
use Psr\Http\Message\ResponseInterface;
use League\Route\Strategy\ParamStrategy;
use Bassett\Infrastructure\Router\Router;
use Psr\Http\Message\ServerRequestInterface;
use Bassett\Infrastructure\Router\JsonParamStrategy;

define('APP_DIR', __DIR__ . '/..');

$providers = require_once '../config/services.php';
$routes    = require_once '../config/routes.php';
$apiRoutes = require_once '../config/api-routes.php';
$container = new Container;

// Load service providers
foreach ($providers as $provider) {
    $container->addServiceProvider($provider);
}

$strategy = (new ParamStrategy)->setContainer($container);
$router = (new Router($strategy))->register($routes);

$container->get('emitter')->emit($router->dispatch());
