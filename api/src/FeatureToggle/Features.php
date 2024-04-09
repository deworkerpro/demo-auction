<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Override;

final class Features implements FeatureFlag, FeatureSwitch, FeaturesContext
{
    /**
     * @param array<string,bool> $features
     */
    public function __construct(private array $features) {}

    #[Override]
    public function isEnabled(string $name): bool
    {
        if (!\array_key_exists($name, $this->features)) {
            return false;
        }
        return $this->features[$name];
    }

    #[Override]
    public function enable(string $name): void
    {
        $this->features[$name] = true;
    }

    #[Override]
    public function disable(string $name): void
    {
        $this->features[$name] = false;
    }

    #[Override]
    public function getAllEnabled(): array
    {
        return array_keys(array_filter($this->features));
    }
}
