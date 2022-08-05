<?php

declare(strict_types=1);

namespace App\Serializer;

use ArrayObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class Normalizer
{
    public function __construct(
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public function normalize(mixed $object): array|string|int|float|bool|ArrayObject|null
    {
        return $this->normalizer->normalize($object);
    }
}
