<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Monolog\Processor\ProcessorInterface;

class FeaturesMonologProcessor implements ProcessorInterface
{
    private FeaturesContext $context;

    public function __construct(FeaturesContext $context)
    {
        $this->context = $context;
    }

    public function __invoke(array $record): array
    {
        return array_merge_recursive($record, [
            'extra' => [
                'features' => $this->context->getAllEnabled(),
            ],
        ]);
    }
}
