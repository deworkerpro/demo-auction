<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class Denormalizer
{
    public function __construct(
        private readonly DenormalizerInterface $denormalizer
    ) {
    }

    public function denormalize(mixed $data, string $type): object
    {
        /** @var object */
        return $this->denormalizer->denormalize($data, $type, null, [
            DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
        ]);
    }
}
