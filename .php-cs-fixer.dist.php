<?php
$finder = PhpCsFixer\Finder::create()
    ->in(
        [
            __DIR__ . '/src/',
            __DIR__ . '/tests/',
        ]
    )
    ->exclude([
        'cache',
        'wordpress',
        'woocommerce',
        '_data',
        '_output',
        '_support',
    ])
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        'native_function_invocation' => true, //https://cs.symfony.com/doc/rules/function_notation/native_function_invocation.html
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
