<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Response\JsonResponse;
use DomainException;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class DomainExceptionHandler implements MiddlewareInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private TranslatorInterface $translator
    ) {}

    #[Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (DomainException $exception) {
            $this->logger->warning($exception->getMessage(), [
                'exception' => $exception,
                'url' => (string)$request->getUri(),
            ]);
            return new JsonResponse([
                'message' => $this->translator->trans($exception->getMessage(), [], 'exceptions'),
            ], 409);
        }
    }
}
