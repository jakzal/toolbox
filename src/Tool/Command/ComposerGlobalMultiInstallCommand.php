<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;

final class ComposerGlobalMultiInstallCommand implements Command
{
    private $commands;

    public function __construct(Collection $commands)
    {
        $this->commands = $commands->filter(function (ComposerGlobalInstallCommand $c) {
            return $c;
        });
    }

    public function __toString(): string
    {
        $packages = \implode(' ', \array_map(function (ComposerGlobalInstallCommand $command) {
            return $command->package();
        }, $this->commands->toArray()));

        return \sprintf('composer global require --no-suggest --prefer-dist --update-no-dev -n %s', $packages);
    }
}
