<?php

declare(strict_types=1);

namespace App\Frontend;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FrontendUrlTwigExtension extends AbstractExtension
{
    private FrontendUrlGenerator $url;

    public function __construct(FrontendUrlGenerator $url)
    {
        $this->url = $url;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('frontend_url', [$this, 'url']),
        ];
    }

    public function url(string $path, array $params = []): string
    {
        return $this->url->generate($path, $params);
    }
}
