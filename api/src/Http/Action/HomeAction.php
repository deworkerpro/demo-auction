<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use stdClass;

class HomeAction
{
    public function __invoke(Request $request, Response $response, $args): Response
    {
        return Http::json($response, new stdClass());
    }
}
