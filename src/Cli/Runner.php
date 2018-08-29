<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli;

use Zalas\Toolbox\Tool\Command;

class Runner
{
    public function run(Command $command): int
    {
        \passthru((string) $command, $status);

        return $status;
    }
}
