<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;

final class PharDownloadCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['phar', 'bin'], $command, 'PharDownloadCommand');

        return new PharDownloadCommand($command['phar'], $command['bin']);
    }
}
