<?php declare(strict_types=1);

namespace Zalas\Toolbox\Runner;

use Zalas\Toolbox\Tool\Command;

final class PassthruRunner implements Runner
{
    public function run(Command $command): int
    {
        \passthru((string) $command, $status);

        return $status;
    }
}
