#!/usr/bin/env php
<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Zalas\Toolbox\Json\PhpVersionsParser;

$composerPath = __DIR__ . '/../composer.json';
$metadataPath = __DIR__ . '/../metadata.json';

$composer = json_decode(file_get_contents($composerPath), true);
$versions = PhpVersionsParser::parse($composer['require']['php']);

file_put_contents($metadataPath, json_encode(['php_versions' => $versions], JSON_PRETTY_PRINT) . "\n");
