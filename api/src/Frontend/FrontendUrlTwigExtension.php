<?php

declare(strict_types=1);

namespace App\Frontend;

use Override;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FrontendUrlTwigExtension extends AbstractExtension
{
    public function __construct(private readonly FrontendUrlGenerator $url) {}

    #[Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction('frontend_url', $this->url(...)),
        ];
    }

    public function url(string $path, array $params = []): string
    {
        return $this->url->generate($path, $params);
    }
}
