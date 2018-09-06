<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;

final class ComposerInstallCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['repository', 'target-dir'], $command, 'ComposerInstallCommand');

        return new ComposerInstallCommand($command['repository'], $command['target-dir'], $command['version'] ?? null);
    }
}
