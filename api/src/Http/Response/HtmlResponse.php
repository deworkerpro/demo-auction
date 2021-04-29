<?php

declare(strict_types=1);

namespace App\Http\Response;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

final class HtmlResponse extends Response
{
    public function __construct(string $html, int $status = 200)
    {
        parent::__construct(
            $status,
            new Headers(['Content-Type' => 'text/html']),
            (new StreamFactory())->createStream($html)
        );
    }
}
