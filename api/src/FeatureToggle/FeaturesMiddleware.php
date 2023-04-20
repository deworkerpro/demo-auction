<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class FeaturesMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly FeatureSwitch $switch,
        private readonly string $header = 'X-Features'
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine($this->header);
        $features = array_filter(preg_split('/\s*,\s*/', $header));

        foreach ($features as $feature) {
            if (str_starts_with($feature, '!')) {
                $this->switch->disable(substr($feature, 1));
            } else {
                $this->switch->enable($feature);
            }
        }

        return $handler->handle($request);
    }
}
