<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\FileDownloadCommand;

final class FileDownloadCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['url', 'file'], $command, 'FileDownloadCommand');

        return new FileDownloadCommand($command['url'], $command['file']);
    }
}
