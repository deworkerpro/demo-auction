<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\FeatureToggle\FeatureFlag;
use App\Http\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

final class HomeAction implements RequestHandlerInterface
{
    private FeatureFlag $flag;

    public function __construct(FeatureFlag $flag)
    {
        $this->flag = $flag;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->flag->isEnabled('NEW_HOME')) {
            return new JsonResponse(['name' => 'API']);
        }
        return new JsonResponse(new stdClass());
    }
}
