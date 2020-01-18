<?php

declare(strict_types=1);

namespace App\Auth\Test\Unit\Entity\User;

use App\Auth\Entity\User\Status;
use PHPUnit\Framework\TestCase;

/**
 * @covers Status
 */
class StatusTest extends TestCase
{
    public function testWait(): void
    {
        $status = Status::wait();

        self::assertTrue($status->isWait());
        self::assertFalse($status->isActive());
    }

    public function testActive(): void
    {
        $status = Status::active();

        self::assertFalse($status->isWait());
        self::assertTrue($status->isActive());
    }
}
