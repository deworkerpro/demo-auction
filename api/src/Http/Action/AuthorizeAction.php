<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Response\HtmlResponse;
use App\Sentry;
use Exception;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;

final class AuthorizeAction implements RequestHandlerInterface
{
    private AuthorizationServer $server;
    private LoggerInterface $logger;
    private Environment $template;
    private ResponseFactoryInterface $response;
    private Sentry $sentry;

    public function __construct(
        AuthorizationServer $server,
        LoggerInterface $logger,
        Environment $template,
        ResponseFactoryInterface $response,
        Sentry $sentry
    ) {
        $this->server = $server;
        $this->logger = $logger;
        $this->template = $template;
        $this->response = $response;
        $this->sentry = $sentry;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $authRequest = $this->server->validateAuthorizationRequest($request);

            if ($request->getMethod() === 'POST') {
                return $this->server->completeAuthorizationRequest($authRequest, $this->response->createResponse());
            }

            return new HtmlResponse(
                $this->template->render('authorize.html.twig')
            );
        } catch (OAuthServerException $exception) {
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);
            return $exception->generateHttpResponse($this->response->createResponse());
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);
            $this->sentry->capture($exception);
            return (new OAuthServerException('Server error.', 0, 'unknown_error', 500))
                ->generateHttpResponse($this->response->createResponse());
        }
    }
}
