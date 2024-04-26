<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Auth\Query\FindIdByCredentials\Fetcher;
use App\Auth\Query\FindIdByCredentials\Query;
use App\Http\RateLimit;
use App\Http\Response\HtmlResponse;
use App\OAuth\Entity\User;
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

final readonly class AuthorizeAction implements RequestHandlerInterface
{
    public function __construct(
        private AuthorizationServer $server,
        private LoggerInterface $logger,
        private Fetcher $users,
        private Environment $template,
        private ResponseFactoryInterface $response,
        private TranslatorInterface $translator,
        private RateLimit $rateLimit
    ) {}

    #[Override]
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
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

                if (!$this->rateLimit->allowed('auth-' . md5(mb_strtolower(trim($body['email'] ?? ''))), 5, 10)) {
                    $error = $this->translator->trans('error.rate_limit', [], 'oauth');

                    return new HtmlResponse(
                        $this->template->render('authorize.html.twig', compact('query', 'error')),
                        429
                    );
                }

                $query->email = $body['email'] ?? '';
                $query->password = $body['password'] ?? '';

                $user = $this->users->fetch($query);

                if ($user === null) {
                    $error = $this->translator->trans('error.incorrect_credentials', [], 'oauth');

                    return new HtmlResponse(
                        $this->template->render('authorize.html.twig', compact('query', 'error')),
                        400
                    );
                }

                if (!$user->isActive) {
                    $error = $this->translator->trans('error.not_confirmed', [], 'oauth');

                    return new HtmlResponse(
                        $this->template->render('authorize.html.twig', compact('query', 'error')),
                        409
                    );
                }

                $authRequest->setUser(new User($user->id));
                $authRequest->setAuthorizationApproved(true);

                return $this->server->completeAuthorizationRequest($authRequest, $this->response->createResponse());
            }

            return new HtmlResponse(
                $this->template->render('authorize.html.twig', compact('query'))
            );
        } catch (OAuthServerException $exception) {
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);
            return $exception->generateHttpResponse($this->response->createResponse());
        }
    }
}
