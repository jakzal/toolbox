<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;

final class ComposerGlobalInstallCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['package'], $command, 'ComposerGlobalInstallCommand');

        return new ComposerGlobalInstallCommand($command['package']);
    }
}
