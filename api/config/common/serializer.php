<?php

declare(strict_types=1);

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

return [
    SerializerInterface::class => static function (): SerializerInterface {
        return new Serializer([
            new PropertyNormalizer(propertyTypeExtractor: new ReflectionExtractor()),
        ], [
            new JsonEncoder(),
        ]);
    },
];
