#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

(new Zalas\Toolbox\Cli\Application('dev', new Zalas\Toolbox\Cli\ServiceContainer()))->run();
