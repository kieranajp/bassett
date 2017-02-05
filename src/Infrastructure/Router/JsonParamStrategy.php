<?php declare(strict_types=1);

namespace Bassett\Infrastructure\Router;

use RuntimeException;
use League\Route\Route;
use Interop\Container\ContainerInterface;
use League\Route\Strategy\AbstractStrategy;
use League\Route\Strategy\StrategyInterface;
use League\Fractal\Resource\ResourceInterface;
use League\Route\Http\Exception as HttpException;

class JsonParamStrategy extends AbstractStrategy implements StrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function dispatch(callable $controller, array $vars, Route $route = null)
    {
        try {
            $result = $this->getContainer()->call($controller, $vars);

            if (! $result instanceof ResponseInterface) {
                if ($result instanceof ResourceInterface) {
                    $result = $this->getContainer()->get('fractal')->createData($result)->toArray();
                }

                $body = json_encode($result);

                $response = $this->getResponse();
                $response->getBody()->write($body);
            }

            return $response->withAddedHeader('Content-Type', 'application/json');
        } catch (HttpException $e) {
            return $e->buildJsonResponse($this->getResponse());
        }

        throw new RuntimeException('Unable to build a json response from controller return value.');
    }
}
