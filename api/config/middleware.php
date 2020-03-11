<?php

declare(strict_types=1);

use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app): void {
    $app->add(ErrorMiddleware::class);
};
