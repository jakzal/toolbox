<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Command;

trait DefaultTargetDir
{
    private function defaultTargetDir(): string
    {
        return \getenv('TOOLBOX_TARGET_DIR') ? \getenv('TOOLBOX_TARGET_DIR') : '/usr/local/bin';
    }
}
