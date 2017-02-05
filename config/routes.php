<?php declare(strict_types=1);

use Bassett\Application\Controller\HomeController;

return [
    [ 'GET', '/',  [ HomeController::class, 'index' ] ],
];
