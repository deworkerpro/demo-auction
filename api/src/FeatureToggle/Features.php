<?php

declare(strict_types=1);

namespace App\FeatureToggle;

final class Features implements FeatureFlag, FeatureSwitch, FeaturesContext
{
    /**
     * @var array<string,bool>
     */
    private array $features;

    /**
     * @param array<string,bool> $features
     */
    public function __construct(array $features)
    {
        $this->features = $features;
    }

    public function isEnabled(string $name): bool
    {
        if (!\array_key_exists($name, $this->features)) {
            return false;
        }
        return $this->features[$name];
    }

    public function enable(string $name): void
    {
        $this->features[$name] = true;
    }

    public function disable(string $name): void
    {
        $this->features[$name] = false;
    }

    public function getAllEnabled(): array
    {
        return array_keys(array_filter($this->features));
    }
}
