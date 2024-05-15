<?php

declare(strict_types=1);

namespace App\Http\Response;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

final class RedirectResponse extends Response
{
    public function __construct(string $url, int $status = 302)
    {
        parent::__construct(
            $status,
            new Headers(['Location' => $url]),
            (new StreamFactory())->createStreamFromResource(fopen('php://temp', 'rb'))
        );
    }
}
