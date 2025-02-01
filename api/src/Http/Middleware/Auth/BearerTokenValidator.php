<?php

declare(strict_types=1);

namespace App\Http\Middleware\Auth;

use DateInterval;
use DateTimeZone;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Exception;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\CryptKeyInterface;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Override;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BearerTokenValidator implements AuthorizationValidatorInterface
{
    use CryptTrait;

    private CryptKeyInterface $publicKey;
    private AccessTokenRepositoryInterface $accessTokenRepository;
    private Configuration $jwtConfiguration;
    private ?DateInterval $jwtValidAtDateLeeway;

    public function __construct(
        AccessTokenRepositoryInterface $accessTokenRepository,
        ?DateInterval $jwtValidAtDateLeeway = null
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->jwtValidAtDateLeeway = $jwtValidAtDateLeeway;
    }

    public function setPublicKey(CryptKeyInterface $key): void
    {
        $this->publicKey = $key;

        $this->initJwtConfiguration();
    }

    #[Override]
    public function validateAuthorization(ServerRequestInterface $request): ServerRequestInterface
    {
        if ($request->hasHeader('authorization') === false) {
            throw OAuthServerException::accessDenied('Missing "Authorization" header');
        }

        $header = $request->getHeader('authorization');
        $jwt = trim((string)preg_replace('/^\s*Bearer\s/', '', $header[0]));

        if ($jwt === '') {
            throw OAuthServerException::accessDenied('Missing "Bearer" token');
        }

        try {
            $token = $this->jwtConfiguration->parser()->parse($jwt);
        } catch (Exception $exception) {
            throw OAuthServerException::accessDenied($exception->getMessage(), null, $exception);
        }

        try {
            $constraints = $this->jwtConfiguration->validationConstraints();
            $this->jwtConfiguration->validator()->assert($token, ...$constraints);
        } catch (RequiredConstraintsViolated $exception) {
            throw OAuthServerException::accessDenied('Access token could not be verified', null, $exception);
        }

        if (!$token instanceof UnencryptedToken) {
            throw OAuthServerException::accessDenied('Access token is not an instance of UnencryptedToken');
        }

        $claims = $token->claims();

        if ($this->accessTokenRepository->isAccessTokenRevoked((string)$claims->get('jti'))) {
            throw OAuthServerException::accessDenied('Access token has been revoked');
        }

        return $request
            ->withAttribute('oauth_access_token_id', $claims->get('jti'))
            ->withAttribute('oauth_client_id', $this->convertSingleRecordAudToString($claims->get('aud')))
            ->withAttribute('oauth_user_id', $claims->get('sub'))
            ->withAttribute('oauth_user_role', $claims->get('role'))
            ->withAttribute('oauth_scopes', $claims->get('scopes'));
    }

    private function initJwtConfiguration(): void
    {
        $this->jwtConfiguration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText('empty', 'empty')
        )->withValidationConstraints(
            new LooseValidAt(
                new SystemClock(new DateTimeZone(date_default_timezone_get())),
                $this->jwtValidAtDateLeeway
            ),
            new SignedWith(
                new Sha256(),
                InMemory::plainText(
                    $this->publicKey->getKeyContents() ?: throw new RuntimeException('Empty value.'),
                    $this->publicKey->getPassPhrase() ?? ''
                )
            )
        );
    }

    private function convertSingleRecordAudToString(mixed $aud): mixed
    {
        return \is_array($aud) && \count($aud) === 1 ? $aud[0] : $aud;
    }
}
