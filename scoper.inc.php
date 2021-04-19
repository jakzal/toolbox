<?php declare(strict_types=1);

return [
    // Whitelist globals so that Symfony polyfills are not scoped
    'whitelist-global-constants' => true,
    'whitelist-global-classes' => true,
    'whitelist-global-functions' => true,
    'files-whitelist' => [
        'vendor/symfony/polyfill-php80/Resources/stubs/Stringable.php',
    ],
];
