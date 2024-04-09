<?php

declare(strict_types=1);

namespace App\Serializer;

use ArrayObject;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class Normalizer
{
    public function __construct(
        private NormalizerInterface $normalizer
    ) {}

    public function normalize(mixed $object): null|array|ArrayObject|bool|float|int|string
    {
        return $this->normalizer->normalize($object);
    }
}
