<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Auth\Query\FindIdByCredentials\Fetcher;
use App\Auth\Query\FindIdByCredentials\Query;
use App\Http\Response\HtmlResponse;
use App\Http\Response\RedirectResponse;
use App\OAuth\Entity\User;
use App\OAuthClient\OAuthClient;
use DateTimeImmutable;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\Modifier\SameSite;
use Dflydev\FigCookies\SetCookie;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Override;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final readonly class Authorize implements RequestHandlerInterface
{
    public function __construct(
        private AuthorizationServer $server,
        private LoggerInterface $logger,
        private Fetcher $users,
        private Environment $template,
        private ResponseFactoryInterface $response,
        private TranslatorInterface $translator,
        private OAuthClient $client
    ) {}

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /**
         * @var array{
         *     state?: ?string,
         *     provider?: ?string
         * } $params
         */
        $params = $request->getQueryParams();

        $provider = $params['provider'] ?? null;

        if (\is_string($provider) && $provider !== '') {
            try {
                $this->server->validateAuthorizationRequest($request);

                $state = (string)($params['state'] ?? null);

                $response = new RedirectResponse($this->client->generateAuthUrl($provider, $state));

                $cookie = SetCookie::create('auth_query')
                    ->withValue(json_encode($request->getQueryParams(), JSON_THROW_ON_ERROR))
                    ->withExpires(new DateTimeImmutable('+10 minutes'))
                    ->withPath('/oauth')
                    ->withHttpOnly()
                    ->withSameSite(SameSite::lax());

                return FigResponseCookies::set($response, $cookie);
            } catch (OAuthServerException $exception) {
                return $exception->generateHttpResponse($this->response->createResponse());
            }
        }

        try {
            $authRequest = $this->server->validateAuthorizationRequest($request);

            $query = new Query();

            if ($request->getMethod() === 'POST') {
                /**
                 * @var array{
                 *     email?: ?string,
                 *     password?: ?string
                 * } $body
                 */
                $body = $request->getParsedBody();

                $query->email = $body['email'] ?? '';
                $query->password = $body['password'] ?? '';

                $user = $this->users->fetch($query);

                if ($user === null) {
                    $error = $this->translator->trans('error.incorrect_credentials', [], 'oauth');

                    return new HtmlResponse(
                        $this->template->render('authorize.html.twig', compact('query', 'error', 'params')),
                        400
                    );
                }

                if (!$user->isActive) {
                    $error = $this->translator->trans('error.not_confirmed', [], 'oauth');

                    return new HtmlResponse(
                        $this->template->render('authorize.html.twig', compact('query', 'error', 'params')),
                        409
                    );
                }

                $authRequest->setUser(new User($user->id));
                $authRequest->setAuthorizationApproved(true);

                return $this->server->completeAuthorizationRequest($authRequest, $this->response->createResponse());
            }

            return new HtmlResponse(
                $this->template->render('authorize.html.twig', compact('query', 'params'))
            );
        } catch (OAuthServerException $exception) {
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);
            return $exception->generateHttpResponse($this->response->createResponse());
        }
    }
}
