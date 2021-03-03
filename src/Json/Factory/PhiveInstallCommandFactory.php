<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\PhiveInstallCommand;

final class PhiveInstallCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['alias', 'bin'], $command, 'PhiveInstallCommand');

        return new PhiveInstallCommand($command['alias'], $command['bin'], $command['sig'] ?? null);
    }
}
