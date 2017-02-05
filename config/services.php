<?php declare(strict_types=1);

use Bassett\Infrastructure\ServiceProvider\SecurityServiceProvider;
use Bassett\Infrastructure\ServiceProvider\ApplicationServiceProvider;

return [
    ApplicationServiceProvider::class,
    SecurityServiceProvider::class,
];
