<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class FileDownloadCommand implements Command
{
    private $url;
    private $file;

    public function __construct(string $url, string $file)
    {
        $this->url = $url;
        $this->file = $file;
    }

    public function __toString(): string
    {
        return \sprintf('curl -Ls -w %%{filename_effective}\'\n\' %s -o %s', $this->url, $this->file);
    }
}
