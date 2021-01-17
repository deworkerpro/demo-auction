<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test\Unit;

use App\FeatureToggle\Features;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\FeatureToggle\Features
 */
class FeaturesTest extends TestCase
{
    public function testInitial(): void
    {
        $features = new Features($source = [
            'FIRST' => true,
            'SECOND' => false,
        ]);

        self::assertTrue($features->isEnabled('FIRST'));
        self::assertFalse($features->isEnabled('SECOND'));
        self::assertFalse($features->isEnabled('THIRD'));
    }
}
