<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class FeatureFlagTwigExtension extends AbstractExtension
{
    private FeatureFlag $flag;

    public function __construct(FeatureFlag $flag)
    {
        $this->flag = $flag;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_feature_enabled', [$this, 'isFeatureEnabled']),
        ];
    }

    public function isFeatureEnabled(string $name): bool
    {
        return $this->flag->isEnabled($name);
    }
}
