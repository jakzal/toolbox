<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;
use Zalas\Toolbox\Tool\Command\TestCommand;
use Zalas\Toolbox\Tool\Tool;

final class ToolFactory
{
    public static function import(array $tool): Tool
    {
        Assert::requireFields(['name', 'summary', 'website', 'command', 'test'], $tool, 'tool');

        return new Tool(
            $tool['name'],
            $tool['summary'],
            $tool['website'],
            $tool['tags'] ?? [],
            self::importCommand($tool),
            new TestCommand($tool['test'], $tool['name'])
        );
    }

    private static function importCommand(array $tool): Command
    {
        $commands = Collection::create([]);

        foreach ($tool['command'] as $type => $command) {
            $commands = $commands->merge(self::createCommands($type, $command));
        }

        if (0 === $commands->count()) {
            throw new \RuntimeException(\sprintf('No valid command defined for the tool: %s', \json_encode($tool)));
        }

        return 1 === $commands->count() ? $commands->toArray()[0] : new MultiStepCommand($commands);
    }

    private static function createCommands($type, $command): Collection
    {
        $factories = [
            'phar-download' => \sprintf('%s::import', PharDownloadCommandFactory::class),
            'file-download' => \sprintf('%s::import', FileDownloadCommandFactory::class),
            'box-build' => \sprintf('%s::import', BoxBuildCommandFactory::class),
            'composer-install' => \sprintf('%s::import', ComposerInstallCommandFactory::class),
            'composer-global-install' => \sprintf('%s::import', ComposerGlobalInstallCommandFactory::class),
            'composer-bin-plugin' => \sprintf('%s::import', ComposerBinPluginCommandFactory::class),
        ];

        if (!isset($factories[$type])) {
            throw new \RuntimeException(\sprintf('Unrecognised command: "%s". Supported commands are: "%s".', $type, \implode(', ', \array_keys($factories))));
        }

        $command = !\is_numeric(\key($command)) ? [$command] : $command;

        return Collection::create(\array_map(function ($c) use ($type, $factories) {
            return $factories[$type]($c);
        }, $command));
    }
}
