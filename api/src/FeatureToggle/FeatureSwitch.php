<?php

declare(strict_types=1);

namespace App\FeatureToggle;

interface FeatureSwitch
{
    public function enable(string $name): void;
}
