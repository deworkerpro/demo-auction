<?php

declare(strict_types=1);

namespace App\Serializer\Test;

use App\Serializer\Denormalizer;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @covers \App\Serializer\Denormalizer
 *
 * @internal
 */
final class DenormalizerTest extends TestCase
{
    public function testValid(): void
    {
        $origin = $this->createMock(DenormalizerInterface::class);
        $origin->expects(self::once())->method('denormalize')
            ->with(
                ['name' => 'John'],
                stdClass::class,
                null,
                [
                    DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
                    AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                ]
            )
            ->willReturn($object = new stdClass());

        $denormalizer = new Denormalizer($origin);

        $result = $denormalizer->denormalize(['name' => 'John'], stdClass::class);

        self::assertSame($object, $result);
    }

    public function testNotValid(): void
    {
        $origin = $this->createStub(DenormalizerInterface::class);
        $origin->method('denormalize')->willThrowException(
            $exception = new PartialDenormalizationException([], [])
        );

        $denormalizer = new Denormalizer($origin);

        $this->expectExceptionObject($exception);

        $denormalizer->denormalize(['name' => 42], stdClass::class);
    }
}
