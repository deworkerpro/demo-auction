<?php

declare(strict_types=1);

namespace App\FeatureToggle;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Override;

final class FeaturesMonologProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly FeaturesContext $context
    ) {}

    #[Override]
    public function __invoke(LogRecord $record): LogRecord
    {
        $record->extra['features'] = $this->context->getAllEnabled();

        return $record;
    }
}
