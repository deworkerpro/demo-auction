<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test;

use App\FeatureToggle\FeaturesContext;
use App\FeatureToggle\FeaturesMonologProcessor;
use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FeatureMonologProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $context = self::createStub(FeaturesContext::class);
        $context->method('getAllEnabled')->willReturn($source = ['ONE', 'TWO']);

        $processor = new FeaturesMonologProcessor($context);

        $date = new DateTimeImmutable();

        $result = $processor(new LogRecord(
            datetime: $date,
            channel: 'channel',
            level: Level::Warning,
            message: 'Message',
            context: ['name' => 'value'],
            extra: ['param' => 'value'],
        ));

        self::assertEquals([
            'message' => 'Message',
            'context' => ['name' => 'value'],
            'level' => Level::Warning->value,
            'level_name' => 'WARNING',
            'channel' => 'channel',
            'datetime' => $date,
            'extra' => [
                'param' => 'value',
                'features' => $source,
            ],
        ], $result->toArray());
    }
}
