<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Auth\Command\AttachNetwork\Command as AttachNetworkCommand;
use App\Auth\Command\AttachNetwork\Handler as AttachNetworkHandler;
use App\Auth\Command\JoinByNetwork\Command as JoinByNetworkCommand;
use App\Auth\Command\JoinByNetwork\Handler as JoinByNetworkHandler;
use App\Auth\Query\FindIdByEmail\Fetcher as FindIdByEmail;
use App\Auth\Query\FindIdByNetwork\Fetcher as FindIdByNetwork;
use App\OAuth\Entity\User;
use App\OAuthClient\Identity;
use App\OAuthClient\OAuthClient;
use Dflydev\FigCookies\FigResponseCookies;
use DomainException;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Override;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Routing\RouteContext;

final readonly class OAuth implements RequestHandlerInterface
{
    public function __construct(
        private AuthorizationServer $server,
        private ResponseFactoryInterface $response,
        private ServerRequestFactoryInterface $request,
        private LoggerInterface $logger,
        private OAuthClient $client,
        private FindIdByNetwork $findIdByNetwork,
        private FindIdByEmail $findIdByEmail,
        private AttachNetworkHandler $attachNetworkHandler,
        private JoinByNetworkHandler $joinByNetworkHandler,
    ) {}

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array{
         *     code?: string,
         *     state?: string,
         *     error?: string
         * } $params
         */
        $params = $request->getQueryParams();

        $provider = RouteContext::fromRequest($request)->getRoute()?->getArgument('provider') ?? '';
        $code = $params['code'] ?? '';
        $state = $params['state'] ?? '';
        $error = $params['error'] ?? '';

        /**
         * @var array{auth_query?: string} $cookie
         */
        $cookie = $request->getCookieParams();

        /**
         * @var array{state: string} $prevParams
         */
        $prevParams = array_replace(
            ['state' => ''],
            (array)json_decode($cookie['auth_query'] ?? '[]', true)
        );

        try {
            $prevRequest = $this->request->createServerRequest('GET', '/authorize?' . http_build_query($prevParams));
            $authRequest = $this->server->validateAuthorizationRequest($prevRequest);

            if ($state !== $prevParams['state']) {
                throw OAuthServerException::invalidRequest('state');
            }

            if (!empty($error)) {
                throw new OAuthServerException(
                    message: $error,
                    code: 0,
                    errorType: 'service_error',
                    httpStatusCode: 500,
                    hint: $error,
                    redirectUri: $authRequest->getRedirectUri()
                );
            }

            if (empty($code)) {
                throw OAuthServerException::invalidRequest('code');
            }

            $identity = $this->client->getIdentity($provider, $code);

            try {
                $user = $this->getOrCreateByNetwork($provider, $identity);
            } catch (DomainException $exception) {
                throw new OAuthServerException(
                    message: $exception->getMessage(),
                    code: 0,
                    errorType: 'auth_error',
                    httpStatusCode: 500,
                    hint: $exception->getMessage(),
                    redirectUri: $authRequest->getRedirectUri(),
                    previous: $exception
                );
            }

            $authRequest->setUser($user);
            $authRequest->setAuthorizationApproved(true);

            $response = $this->server->completeAuthorizationRequest($authRequest, $this->response->createResponse());

            return FigResponseCookies::remove($response, 'auth_query');
        } catch (OAuthServerException $exception) {
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);
            return $exception->generateHttpResponse($this->response->createResponse());
        }
    }

    private function getOrCreateByNetwork(string $provider, Identity $identity): User
    {
        $user = $this->findIdByNetwork->fetch($provider, $identity->id);

        if ($user !== null) {
            return new User($user->id);
        }

        $user = $this->findIdByEmail->fetch($identity->email);

        if ($user !== null) {
            $this->attachNetworkHandler->handle(new AttachNetworkCommand(
                id: $user->id,
                network: $provider,
                identity: $identity->id
            ));

            return new User($user->id);
        }

        $this->joinByNetworkHandler->handle(new JoinByNetworkCommand(
            email: $identity->email,
            network: $provider,
            identity: $identity->id
        ));

        $user = $this->findIdByNetwork->fetch($provider, $identity->id);

        if ($user === null) {
            throw new DomainException('Unable to load profile.');
        }

        return new User($user->id);
    }
}
