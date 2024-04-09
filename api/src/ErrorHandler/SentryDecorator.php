<?php

declare(strict_types=1);

namespace App\ErrorHandler;

use App\Sentry;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\ErrorHandlerInterface;
use Throwable;

final readonly class SentryDecorator implements ErrorHandlerInterface
{
    public function __construct(
        private ErrorHandlerInterface $next,
        private Sentry $sentry
    ) {}

    #[Override]
    public function __invoke(
        ServerRequestInterface $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ): ResponseInterface {
        $this->sentry->capture($exception);

        return ($this->next)(
            $request,
            $exception,
            $displayErrorDetails,
            $logErrors,
            $logErrorDetails
        );
    }
}
