<?php declare(strict_types=1);

namespace Zalas\Toolbox\UseCase;

use Closure;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;
use Zalas\Toolbox\Tool\Command\ComposerGlobalMultiInstallCommand;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;
use Zalas\Toolbox\Tool\Command\OptimisedComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;

class InstallTools
{
    public const PRE_INSTALLATION_TAG = 'pre-installation';

    private $tools;

    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function __invoke(Filter $filter): Command
    {
        $tools = $this->tools->all($filter);
        $installationCommands = $this->installationCommands($tools);
        $commandFilter = $this->commandFilter($this->toolCommands($tools));

        return new MultiStepCommand(
            $installationCommands
                ->merge($commandFilter(ShCommand::class))
                ->merge($commandFilter(PharDownloadCommand::class))
                ->merge($commandFilter(MultiStepCommand::class))
                ->merge($this->groupComposerGlobalInstallCommands($commandFilter(ComposerGlobalInstallCommand::class)))
                ->merge($this->groupComposerBinPluginCommands($commandFilter(ComposerBinPluginCommand::class)))
                ->merge($commandFilter(ComposerInstallCommand::class))
                ->merge($commandFilter(BoxBuildCommand::class))
        );
    }

    private function commandFilter(Collection $commands): Closure
    {
        return function ($type) use ($commands) {
            return $commands->filter(function (Command $command) use ($type) {
                return $command instanceof $type;
            });
        };
    }

    private function installationCommands(Collection $tools)
    {
        return $tools->filter(function (Tool $tool) {
            return \in_array(self::PRE_INSTALLATION_TAG, $tool->tags());
        })->map(function (Tool $tool) {
            return $tool->command();
        });
    }

    private function toolCommands(Collection $tools)
    {
        return $tools->filter(function (Tool $tool) {
            return !\in_array(self::PRE_INSTALLATION_TAG, $tool->tags());
        })->map(function (Tool $tool) {
            return $tool->command();
        });
    }

    private function groupComposerGlobalInstallCommands(Collection $commands): Collection
    {
        $commands = $commands->empty() ? [] : [new ComposerGlobalMultiInstallCommand($commands)];

        return Collection::create($commands);
    }

    private function groupComposerBinPluginCommands(Collection $commands): Collection
    {
        $commands = $commands->empty() ? [] : [new OptimisedComposerBinPluginCommand($commands)];

        return Collection::create($commands);
    }
}
