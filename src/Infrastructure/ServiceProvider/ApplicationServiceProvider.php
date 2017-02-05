<?php declare(strict_types=1);

namespace Bassett\Infrastructure\ServiceProvider;

use League\Plates\Engine;
use League\Fractal\Manager;
use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ApplicationServiceProvider extends AbstractServiceProvider
{
    /**
     * The provides array is a way to let the container
     * know that a service is provided by this service
     * provider. Every service that is registered via
     * this service provider must have an alias added
     * to this array or it will be ignored.
     *
     * @var array
     */
    protected $provides = [
        'emitter',
        'fractal',
        Engine::class,
        ResponseInterface::class,
        ServerRequestInterface::class
    ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to, but remember, every alias registered
     * within this method must be declared in the `$provides` array.
     */
    public function register()
    {
        $this->getContainer()->share('emitter', SapiEmitter::class);

        $this->getContainer()->share('fractal', Manager::class);

        $this->getContainer()->share(Engine::class, function () {
            return new Engine(APP_DIR . '/views');
        });

        $this->getContainer()->share(ResponseInterface::class, Response::class);

        $this->getContainer()->share(ServerRequestInterface::class, function () {
            return ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
        });
    }
}
