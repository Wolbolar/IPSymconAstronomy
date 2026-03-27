<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/Astronomy',
        __DIR__ . '/libs'
    ])
    ->append([
        __DIR__ . '/bootstrap.php'
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(false)
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => [
            'default' => 'single_space'
        ],
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => [
            'statements' => ['return']
        ],
        'concat_space' => [
            'spacing' => 'one'
        ],
        'no_superfluous_phpdoc_tags' => false,
        'single_quote' => true,
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays']
        ]
    ])
    ->setFinder($finder);
