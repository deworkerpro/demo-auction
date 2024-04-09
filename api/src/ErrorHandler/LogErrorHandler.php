<?php

declare(strict_types=1);

namespace App\ErrorHandler;

use Override;
use Slim\Handlers\ErrorHandler;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class LogErrorHandler extends ErrorHandler
{
    #[Override]
    protected function writeToErrorLog(): void
    {
        $this->logger->error($this->exception->getMessage(), [
            'exception' => $this->exception,
            'url' => (string)$this->request->getUri(),
        ]);
    }
}
