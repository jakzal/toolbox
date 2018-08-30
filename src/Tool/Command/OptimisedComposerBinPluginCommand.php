<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use InvalidArgumentException;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;

final class OptimisedComposerBinPluginCommand implements Command
{
    private $commands;

    public function __construct(Collection $commands)
    {
        if ($commands->empty()) {
            throw new InvalidArgumentException('Collection of composer bin plugin commands cannot be empty.');
        }

        $this->commands = $commands->filter(function (ComposerBinPluginCommand $command) {
            return $command;
        });
    }

    public function __toString(): string
    {
        return \implode(' && ', $this->commandsToRun($this->packagesGroupedByNamespace()));
    }

    private function packagesGroupedByNamespace(): array
    {
        return $this->commands->reduce([], function (array $packages, ComposerBinPluginCommand $command) {
            $packages[$command->namespace()][] = $command->package();

            return $packages;
        });
    }

    private function commandToRun(string $namespace, array $packages): string
    {
        return \sprintf('composer global bin %s require --no-suggest --prefer-dist --update-no-dev -n %s', $namespace, \implode(' ', $packages));
    }

    private function commandsToRun(array $packagesGrouped): array
    {
        return \array_map([$this, 'commandToRun'], \array_keys($packagesGrouped), $packagesGrouped);
    }
}
