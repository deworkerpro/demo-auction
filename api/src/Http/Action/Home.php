<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\FeatureToggle\FeatureFlag;
use App\Http\Response\JsonResponse;
use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

final readonly class Home implements RequestHandlerInterface
{
    public function __construct(private FeatureFlag $flag) {}

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->flag->isEnabled('NEW_HOME')) {
            return new JsonResponse(['name' => 'API']);
        }
        return new JsonResponse(new stdClass());
    }
}
