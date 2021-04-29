<?php

declare(strict_types=1);

namespace App\OAuth\Test\Unit\Entity;

use App\OAuth\Entity\Scope;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ScopeTest extends TestCase
{
    public function testCreate(): void
    {
        $scope = new Scope($identifier = 'common');

        self::assertSame($identifier, $scope->getIdentifier());
        self::assertSame($identifier, $scope->jsonSerialize());
    }
}
