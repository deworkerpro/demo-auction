<?php

declare(strict_types=1);

namespace App\Http\Response;

use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;

final class JsonResponse extends Response
{
    public function __construct(mixed $data, int $status = 200, bool $isJson = false)
    {
        parent::__construct(
            $status,
            new Headers(['Content-Type' => 'application/json']),
            (new StreamFactory())->createStream($isJson ? (string)$data : json_encode($data, JSON_THROW_ON_ERROR))
        );
    }
}
