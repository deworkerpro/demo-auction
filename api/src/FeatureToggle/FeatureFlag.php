<?php

declare(strict_types=1);

namespace App\FeatureToggle;

interface FeatureFlag
{
    public function isEnabled(string $name): bool;
}
