<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ShCommand;

final class ShCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['command'], $command, 'ShCommand');

        return new ShCommand($command['command']);
    }
}
