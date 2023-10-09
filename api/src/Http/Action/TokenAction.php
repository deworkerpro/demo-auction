<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Sentry;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Override;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Cookies;

final readonly class TokenAction implements RequestHandlerInterface
{
    public function __construct(
        private AuthorizationServer $server,
        private LoggerInterface $logger,
        private ResponseFactoryInterface $response,
        private StreamFactoryInterface $stream,
        private Sentry $sentry
    ) {}

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $request = $request->withParsedBody(array_replace(
            (array)$request->getParsedBody(),
            ['refresh_token' => $request->getCookieParams()['refresh_token'] ?? null],
        ));

        $response = $this->response->createResponse();

        try {
            $response = $this->server->respondToAccessTokenRequest($request, $response);

            /**
             * @var array{
             *     refresh_token: string,
             *     expires_in: int
             * } $data
             */
            $data = json_decode((string)$response->getBody(), true);
            $refreshToken = $data['refresh_token'] ?? null;

            if ($refreshToken === null || $refreshToken === '') {
                return $response;
            }

            unset($data['refresh_token']);

            $cookies = new Cookies();
            $cookies->set('refresh_token', [
                'value' => $refreshToken,
                'expires' => time() + $data['expires_in'],
                'path' => '/token',
                'httponly' => true,
                'secure' => $request->getUri()->getScheme() === 'https',
                'samesite' => 'None',
            ]);

            /**
             * @var array<array-key, string> $cookieHeaders
             */
            $cookieHeaders = $cookies->toHeaders();

            return $response
                ->withBody($this->stream->createStream(json_encode($data)))
                ->withHeader('Set-Cookie', $cookieHeaders);
        } catch (OAuthServerException $exception) {
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);
            return $exception->generateHttpResponse($response);
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $this->sentry->capture($exception);
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
        }
    }
}
