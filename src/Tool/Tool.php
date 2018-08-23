<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

use Zalas\Toolbox\Tool\Command\Assert;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;
use Zalas\Toolbox\Tool\Command\FileDownloadCommand;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;
use Zalas\Toolbox\Tool\Command\TestCommand;

class Tool
{
    private $name;
    private $summary;
    private $website;
    private $command;
    private $testCommand;

    public function __construct(string $name, string $summary, string $website, Command $command, Command $testCommand)
    {
        $this->name = $name;
        $this->summary = $summary;
        $this->website = $website;
        $this->command = $command;
        $this->testCommand = $testCommand;
    }

    public static function import(array $tool): self
    {
        Assert::requireFields(['name', 'summary', 'website', 'command', 'test'], $tool, 'tool');

        return new self($tool['name'], $tool['summary'], $tool['website'], self::importCommand($tool), new TestCommand($tool['test'], $tool['name']));
    }

    public function name(): string
    {
        return $this->name;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    public function website(): string
    {
        return $this->website;
    }

    public function command(): Command
    {
        return $this->command;
    }

    public function testCommand(): Command
    {
        return $this->testCommand;
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
            'phar-download' => \sprintf('%s::import', PharDownloadCommand::class),
            'file-download' => \sprintf('%s::import', FileDownloadCommand::class),
            'box-build' => \sprintf('%s::import', BoxBuildCommand::class),
            'composer-install' => \sprintf('%s::import', ComposerInstallCommand::class),
            'composer-global-install' => \sprintf('%s::import', ComposerGlobalInstallCommand::class),
            'composer-bin-plugin' => \sprintf('%s::import', ComposerBinPluginCommand::class),
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
