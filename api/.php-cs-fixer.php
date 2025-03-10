<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return
    (new Config())
        ->setCacheFile(__DIR__ . '/var/cache/.php_cs')
        ->setParallelConfig(ParallelConfigFactory::detect())
        ->setFinder(
            Finder::create()
                ->in([
                    __DIR__ . '/bin',
                    __DIR__ . '/config',
                    __DIR__ . '/public',
                    __DIR__ . '/src',
                    __DIR__ . '/tests',
                    __DIR__ . '/translations',
                    __DIR__ . '/rector',
                ])
                ->append([
                    __FILE__,
                    __DIR__ . '/rector.php',
                ])
        )
        ->setRiskyAllowed(true)
        ->setRules([
            '@PER-CS' => true,
            '@PER-CS:risky' => true,
            '@PHP80Migration' => true,
            '@PHP80Migration:risky' => true,
            '@PHP81Migration' => true,
            '@PHP82Migration' => true,
            '@PHP83Migration' => true,
            '@PHP84Migration' => true,
            '@PHPUnit84Migration:risky' => true,
            '@PHPUnit100Migration:risky' => true,
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,

            'ordered_imports' => ['imports_order' => ['class', 'function', 'const']],

            'concat_space' => ['spacing' => 'one'],
            'cast_spaces' => ['space' => 'none'],
            'binary_operator_spaces' => false,

            'phpdoc_to_comment' => false,
            'phpdoc_separation' => false,
            'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
            'phpdoc_align' => false,

            'operator_linebreak' => false,

            'global_namespace_import' => true,

            'blank_line_before_statement' => false,
            'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],

            'fopen_flags' => ['b_mode' => true],

            'php_unit_strict' => false,
            'php_unit_test_class_requires_covers' => false,
            'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],

            'yoda_style' => false,

            'final_class' => true,
            'final_public_method_for_abstract_class' => true,
            'self_static_accessor' => true,

            'static_lambda' => true,
        ]);
