<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User\User;

use App\Auth\Entity\User\Role;
use PHPUnit\Framework\TestCase;
use App\Auth\Test\Builder\UserBuilder;

class RemoveTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->build();

        $user->remove();
    }

    public function testActive(): void
    {
        $user = (new UserBuilder())
            ->active()
            ->build();

        $this->expectExceptionMessage('Unable to remove active user.');

        $user->remove();
    }
}
