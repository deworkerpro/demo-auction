<?php

declare(strict_types=1);

namespace App\Http\Response;

use RuntimeException;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

final class RedirectResponse extends Response
{
    public function __construct(string $url, int $status = 302)
    {
        $resource = fopen('php://temp', 'rb');

        if ($resource === false) {
            throw new RuntimeException('Unable to open resource.');
        }

        parent::__construct(
            $status,
            new Headers(['Location' => $url]),
            new StreamFactory()->createStreamFromResource($resource)
        );
    }
}
