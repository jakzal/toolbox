<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;

final class BoxBuildCommandFactory
{
    public static function import(array $definition): Command
    {
        Assert::requireFields(['repository', 'phar', 'bin'], $definition, 'BoxBuildCommand');

        return new BoxBuildCommand($definition['repository'], $definition['phar'], $definition['bin'], \sys_get_temp_dir(), $definition['version'] ?? null);
    }
}
