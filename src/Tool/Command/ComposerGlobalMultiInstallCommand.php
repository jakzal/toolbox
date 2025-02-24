<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use InvalidArgumentException;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;

final class ComposerGlobalMultiInstallCommand implements Command
{
    private Collection $commands;

    public function __construct(Collection $commands)
    {
        if ($commands->empty()) {
            throw new InvalidArgumentException('Collection of composer global install commands cannot be empty.');
        }

        $this->commands = $commands->filter(function (ComposerGlobalInstallCommand $c) {
            return $c;
        });
    }

    public function __toString(): string
    {
        $packages = \implode(' ', \array_map(function (ComposerGlobalInstallCommand $command) {
            return $command->package();
        }, $this->commands->toArray()));

        return \sprintf('composer global require --prefer-dist --update-no-dev -n %s', $packages);
    }
}
