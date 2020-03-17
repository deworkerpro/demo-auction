<?php

declare(strict_types=1);

use App\Http\Middleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return static function (App $app): void {
    $app->add(Middleware\DomainExceptionHandler::class);
    $app->add(Middleware\ValidationExceptionHandler::class);
    $app->add(Middleware\ClearEmptyInput::class);
    $app->add(Middleware\TranslatorLocale::class);
    $app->addBodyParsingMiddleware();
    $app->add(ErrorMiddleware::class);
};
