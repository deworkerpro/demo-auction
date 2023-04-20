<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

return [
    NormalizerInterface::class => DI\get(SerializerInterface::class),
    DenormalizerInterface::class => DI\get(SerializerInterface::class),

    SerializerInterface::class => static function (ContainerInterface $container): SerializerInterface {
        /**
         * @psalm-suppress MixedArrayAccess
         * @var array{
         *     normalizers: array<array-key, class-string<DenormalizerInterface>|class-string<NormalizerInterface>>
         * } $config
         */
        $config = $container->get('config')['serializer'];

        $normalizers = array_map(
            static fn (string $name) => $container->get($name),
            $config['normalizers']
        );

        return new Serializer([
            ...$normalizers,
            new DateTimeNormalizer(),
            new PropertyNormalizer(
                classMetadataFactory: new ClassMetadataFactory(new AttributeLoader()),
                propertyTypeExtractor: new PropertyInfoExtractor(
                    typeExtractors: [
                        new PhpDocExtractor(),
                        new ReflectionExtractor(),
                    ]
                )
            ),
            new ArrayDenormalizer(),
        ], [
            new JsonEncoder(),
        ]);
    },

    'config' => [
        'serializer' => [
            'normalizers' => [],
        ],
    ],
];
