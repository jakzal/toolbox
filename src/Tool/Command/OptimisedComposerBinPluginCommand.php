<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use InvalidArgumentException;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;

final class OptimisedComposerBinPluginCommand implements Command
{
    private Collection $commands;

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
        return \implode(' && ', \array_merge($this->commandsToRun($this->packagesGroupedByNamespace()), $this->linksToCreate()));
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
        return \sprintf('composer global bin %s require --prefer-dist --update-no-dev -n %s', $namespace, \implode(' ', $packages));
    }

    private function commandsToRun(array $packagesGrouped): array
    {
        return \array_map([$this, 'commandToRun'], \array_keys($packagesGrouped), $packagesGrouped);
    }

    private function linksToCreate(): array
    {
        return $this->commands
            ->filter(function (ComposerBinPluginCommand $command) {
                return !$command->links()->empty();
            })
            ->map(function (ComposerBinPluginCommand $command) {
                return $command->links()->reduce('', function (string $command, ComposerBinPluginLinkCommand $link) {
                    return !empty($command) ? $command.' && '.$link : $link;
                });
            })
            ->toArray();
    }
}
