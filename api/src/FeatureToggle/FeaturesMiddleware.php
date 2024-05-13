<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Override;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class FeaturesMiddleware implements MiddlewareInterface
{
    public function __construct(
        private FeatureSwitch $switch,
        private string $header = 'X-Features',
        private string $cookie = 'features',
    ) {}

    #[Override]
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine($this->header);
        $headerFeatures = array_filter(preg_split('/\s*,\s*/', $header));

        $cookie = (string)($request->getCookieParams()[$this->cookie] ?? '');
        $cookieFeatures = array_filter(preg_split('/\s*,\s*/', $cookie));

        $requestedFeatures = array_merge($headerFeatures, $cookieFeatures);

        $features = [];

        foreach ($requestedFeatures as $feature) {
            if (str_starts_with($feature, '!')) {
                $features[substr($feature, 1)] = false;
            } else {
                $features[$feature] = true;
            }
        }

        foreach ($features as $feature => $enable) {
            if ($enable) {
                $this->switch->enable($feature);
            } else {
                $this->switch->disable($feature);
            }
        }

        return $handler->handle($request);
    }
}
