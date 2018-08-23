<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class FileDownloadCommand implements Command
{
    private $url;
    private $file;

    private function __construct(string $url, string $file)
    {
        $this->url = $url;
        $this->file = $file;
    }

    public function __toString(): string
    {
        return \sprintf('curl -Ls -w %%{filename_effective}\'\n\' %s -o %s', $this->url, $this->file);
    }

    public static function import(array $command): Command
    {
        Assert::requireFields(['url', 'file'], $command, 'FileDownloadCommand');

        return new self($command['url'], $command['file']);
    }
}
