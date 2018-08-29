<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class PharDownloadCommand implements Command
{
    private $phar;
    private $bin;

    public function __construct(string $phar, string $bin)
    {
        $this->phar = $phar;
        $this->bin = $bin;
    }

    public function __toString(): string
    {
        return \sprintf('curl -Ls -w %%{filename_effective}\'\n\' %s -o %s && chmod +x %s', $this->phar, $this->bin, $this->bin);
    }
}
