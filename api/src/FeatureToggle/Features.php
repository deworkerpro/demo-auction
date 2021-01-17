<?php

declare(strict_types=1);

namespace App\FeatureToggle;

class Features implements FeatureFlag
{
    public function isEnabled(string $name): bool
    {
        return false;
    }
}
