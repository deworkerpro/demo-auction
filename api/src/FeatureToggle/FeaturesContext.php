<?php

declare(strict_types=1);

namespace App\FeatureToggle;

interface FeaturesContext
{
    public function getAllEnabled(): array;
}
