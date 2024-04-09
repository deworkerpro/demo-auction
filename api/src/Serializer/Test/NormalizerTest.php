<?php

declare(strict_types=1);

namespace App\Serializer\Test;

use App\Serializer\Normalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @internal
 */
#[CoversClass(Normalizer::class)]
final class NormalizerTest extends TestCase
{
    public function testValid(): void
    {
        $object = new stdClass();

        $origin = $this->createMock(NormalizerInterface::class);
        $origin->expects(self::once())->method('normalize')
            ->with($object)
            ->willReturn(['name' => 'John']);

        $normalizer = new Normalizer($origin);

        $result = $normalizer->normalize($object);

        self::assertSame(['name' => 'John'], $result);
    }
}
