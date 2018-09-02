<?php declare(strict_types=1);

namespace Zalas\Toolbox\Runner;

use Zalas\Toolbox\Tool\Command;

interface Runner
{
    public function run(Command $command): int;
}
