<?php

declare(strict_types=1);

namespace App\ErrorHandler;

use Psr\Log\LoggerInterface;
use Slim\Handlers\ErrorHandler;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * TODO: Remove $logger after pull request
 * @property LoggerInterface $logger
 */
class LogErrorHandler extends ErrorHandler
{
    protected function writeToErrorLog(): void
    {
        $this->logger->error($this->exception->getMessage(), [
            'exception' => $this->exception,
            'url' => (string)$this->request->getUri(),
        ]);
    }
}
