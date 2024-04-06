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
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Override;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class BearerTokenValidator implements AuthorizationValidatorInterface
{
    use CryptTrait;

    protected CryptKey $publicKey;

    private AccessTokenRepositoryInterface $accessTokenRepository;
    private Configuration $jwtConfiguration;
    private ?DateInterval $jwtValidAtDateLeeway;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepository, ?DateInterval $jwtValidAtDateLeeway = null)
    {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->jwtValidAtDateLeeway = $jwtValidAtDateLeeway;
    }

    public function setPublicKey(CryptKey $key): void
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
        /** @var non-empty-string $jwt */
        $jwt = trim((string)preg_replace('/^\s*Bearer\s/', '', $header[0]));

        try {
            /** @var Plain $token */
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
        );

        $clock = new SystemClock(new DateTimeZone(date_default_timezone_get()));
        /** @var non-empty-string $keyContents */
        $keyContents = $this->publicKey->getKeyContents();
        $this->jwtConfiguration->setValidationConstraints(
            new LooseValidAt($clock, $this->jwtValidAtDateLeeway),
            new SignedWith(
                new Sha256(),
                InMemory::plainText($keyContents, $this->publicKey->getPassPhrase() ?? '')
            )
        );
    }

    private function convertSingleRecordAudToString(mixed $aud): mixed
    {
        return \is_array($aud) && \count($aud) === 1 ? $aud[0] : $aud;
    }
}
