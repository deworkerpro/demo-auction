<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Role;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Role
 */
class RoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $role = new Role($name = Role::ADMIN);

        self::assertEquals($name, $role->getName());
    }

    public function testIncorrect(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Role('none');
    }

    public function testEmpty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Role('');
    }

    public function testUserFactory(): void
    {
        $role = Role::user();

        self::assertEquals(Role::USER, $role->getName());
    }
}
