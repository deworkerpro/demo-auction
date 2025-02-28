<?php

declare(strict_types=1);

namespace App\Rector\Tests\ConstructorPromotionExceptRector;

use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

/**
 * @internal
 */
final class ConstructorPromotionExceptRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideCases')]
    public function test(string $filePath): void
    {
        $this->doTestFile($filePath);
    }

    /**
     * @return iterable<array-key, array<array-key, string>>
     */
    public static function provideCases(): iterable
    {
        /**
         * @var iterable<array-key, array<array-key, string>>
         */
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[Override]
    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
