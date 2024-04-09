<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withCache(__DIR__ . '/var/cache/.rector', FileCacheStorage::class)
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/translations',
    ])
    // ->withPhpSets(php83: true)
    ->withRules([
        // PHP 8.3
        AddOverrideAttributeToOverriddenMethodsRector::class,
        StringableForToStringRector::class,
        FirstClassCallableRector::class,
        ClosureToArrowFunctionRector::class,
        AddTypeToConstRector::class,
        ReadOnlyPropertyRector::class,

        // Other
        AddVoidReturnTypeWhereNoReturnRector::class,
    ]);
