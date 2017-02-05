<?php declare(strict_types=1);

namespace Bassett\Infrastructure\Router;

use League\Container\Container;
use League\Route\RouteCollection;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ResponseInterface;
use League\Route\Strategy\StrategyInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Http\Exception as HttpException;

class Router
{
    private $container;
    private $router;

    public function __construct(StrategyInterface $strategy)
    {
        $this->container = $strategy->getContainer();
        $this->router = new RouteCollection($this->container);
        $this->router->setStrategy($strategy);
    }

    public function register(array $routes) : Router
    {
        foreach ($routes as $route) {
            $this->router->map($route[0], $route[1], $route[2]);
        }

        return $this;
    }

    public function dispatch()
    {
        $request = $this->container->get(ServerRequestInterface::class);
        $response = $this->container->get(ResponseInterface::class);

        try {
            return $this->router->dispatch($request, $response);
        } catch (HttpException $e) {
            return $e->buildJsonResponse($response);
        }
    }
}
