<?php declare(strict_types=1);

use Bassett\Application\Controller\HomeController;
use Bassett\Application\Controller\PipelineController;

return [
    [ 'GET', '/',  [ HomeController::class, 'index' ] ],
    [ 'GET', '/authenticate',  [ HomeController::class, 'authenticate' ] ],

    [ 'GET', '/pipelines', [ PipelineController::class, 'index'] ],
];
