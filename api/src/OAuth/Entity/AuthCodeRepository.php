<?php

declare(strict_types=1);

namespace App\OAuth\Entity;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use Override;

final readonly class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * @param EntityRepository<AuthCode> $repo
     */
    public function __construct(
        private EntityManagerInterface $em,
        private EntityRepository $repo
    ) {}

    #[Override]
    public function getNewAuthCode(): AuthCode
    {
        return new AuthCode();
    }

    #[Override]
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void
    {
        if ($this->exists($authCodeEntity->getIdentifier())) {
            throw UniqueTokenIdentifierConstraintViolationException::create();
        }

        $this->em->persist($authCodeEntity);
        $this->em->flush();
    }

    #[Override]
    public function revokeAuthCode(string $codeId): void
    {
        if ($code = $this->repo->find($codeId)) {
            $this->em->remove($code);
            $this->em->flush();
        }
    }

    #[Override]
    public function isAuthCodeRevoked(string $codeId): bool
    {
        return !$this->exists($codeId);
    }

    public function removeAllForUser(string $userId): void
    {
        $this->em->createQueryBuilder()
            ->delete(AuthCode::class, 'ac')
            ->andWhere('ac.userIdentifier = :user_id')
            ->setParameter(':user_id', $userId)
            ->getQuery()->execute();
    }

    public function removeAllExpired(DateTimeImmutable $now): void
    {
        $this->em->createQueryBuilder()
            ->delete(AuthCode::class, 'ac')
            ->andWhere('ac.expiryDateTime < :date')
            ->setParameter(':date', $now->format(DATE_ATOM))
            ->getQuery()->execute();
    }

    private function exists(string $id): bool
    {
        return $this->repo->createQueryBuilder('t')
            ->select('COUNT(t.identifier)')
            ->andWhere('t.identifier = :identifier')
            ->setParameter(':identifier', $id)
            ->getQuery()->getSingleScalarResult() > 0;
    }
}
