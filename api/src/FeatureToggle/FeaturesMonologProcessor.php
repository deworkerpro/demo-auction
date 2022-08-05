<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Monolog\Processor\ProcessorInterface;

/**
 * @psalm-import-type Record from \Monolog\Logger
 */
final class FeaturesMonologProcessor implements ProcessorInterface
{
    public function __construct(private readonly FeaturesContext $context)
    {
    }

    public function __invoke(array $record): array
    {
        /** @var Record */
        return array_merge_recursive($record, [
            'extra' => [
                'features' => $this->context->getAllEnabled(),
            ],
        ]);
    }
}
