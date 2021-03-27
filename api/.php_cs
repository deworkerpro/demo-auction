<?php

declare(strict_types=1);

return
    (new PhpCsFixer\Config())
        ->setCacheFile(__DIR__ . '/var/cache/.php_cs')
        ->setFinder(
            PhpCsFixer\Finder::create()
                ->in([
                    __DIR__ . '/bin',
                    __DIR__ . '/config',
                    __DIR__ . '/public',
                    __DIR__ . '/src',
                    __DIR__ . '/tests',
                ])
                ->append([
                    __FILE__,
                ])
        )
        ->setRules([
            '@PSR12' => true,
            '@PSR12:risky' => true,
            '@DoctrineAnnotation' => true,
            '@PHP80Migration' => true,
            '@PHP80Migration:risky' => true,
            '@PHPUnit84Migration:risky' => true,

            'no_unused_imports' => true,
            'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],

            'no_superfluous_phpdoc_tags' => ['remove_inheritdoc' => true],
        ]);
