<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;

final class ComposerBinPluginCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['package', 'namespace'], $command, 'ComposerBinPluginCommand');

        return new ComposerBinPluginCommand($command['package'], $command['namespace']);
    }
}
