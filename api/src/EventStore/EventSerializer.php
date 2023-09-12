<?php

declare(strict_types=1);

namespace App\EventStore;

use App\Serializer\Denormalizer;
use App\Serializer\Normalizer;
use RuntimeException;

final readonly class EventSerializer
{
    public function __construct(
        private Normalizer $normalizer,
        private Denormalizer $denormalizer
    ) {}

    public function serialize(object $event): array
    {
        $result = $this->normalizer->normalize($event);

        if (!\is_array($result)) {
            throw new RuntimeException('Unable to serialize event ' . $event::class);
        }

        return $result;
    }

    public function unserialize(string $type, array $payload): object
    {
        /**
         * @var class-string $type
         */
        return $this->denormalizer->denormalize($payload, $type);
    }
}