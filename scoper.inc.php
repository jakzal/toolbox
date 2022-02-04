<?php declare(strict_types=1);

return [
    // Whitelist globals so that Symfony polyfills are not scoped
    'expose-global-constants' => true,
    'expose-global-classes' => true,
    'expose-global-functions' => true,
    'exclude-files' => [
        'vendor/symfony/polyfill-php80/Resources/stubs/Stringable.php',
        'vendor/symfony/polyfill-php80/bootstrap.php',
    ],
    'exclude-namespaces' => [
        'Symfony\\Polyfill\\Php80\\*'
    ]
];
