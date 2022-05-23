<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class ComposerGlobalInstallCommand implements Command
{
    private $package;

    public function __construct(string $package)
    {
        $this->package = $package;
    }

    public function __toString(): string
    {
        return \sprintf('composer global require --prefer-dist --update-no-dev -n %s', $this->package);
    }

    public function package(): string
    {
        return $this->package;
    }
}
