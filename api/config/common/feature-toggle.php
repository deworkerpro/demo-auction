<?php

declare(strict_types=1);

use App\FeatureToggle\FeatureFlag;
use App\FeatureToggle\Features;

return [
    FeatureFlag::class => DI\get(Features::class),
];
