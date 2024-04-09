<?php

declare(strict_types=1);

namespace App\Frontend\Test;

use App\Frontend\FrontendUrlGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(FrontendUrlGenerator::class)]
final class FrontendUrlGeneratorTest extends TestCase
{
    public function testEmpty(): void
    {
        $generator = new FrontendUrlGenerator('http://test');

        self::assertSame('http://test', $generator->generate(''));
    }

    public function testPath(): void
    {
        $generator = new FrontendUrlGenerator('http://test');

        self::assertSame('http://test/path', $generator->generate('path'));
    }

    public function testWithParams(): void
    {
        $generator = new FrontendUrlGenerator('http://test');

        self::assertSame('http://test/path?a=1&b=2', $generator->generate('path', [
            'a' => '1',
            'b' => 2,
        ]));
    }
}
