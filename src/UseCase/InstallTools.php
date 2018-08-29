<?php declare(strict_types=1);

namespace Zalas\Toolbox\UseCase;

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
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;

class InstallTools
{
    private $tools;

    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function __invoke(): Command
    {
        $commandFilter = $this->commandFilter();

        return new MultiStepCommand(
            $commandFilter(ShCommand::class)
                ->merge($commandFilter(PharDownloadCommand::class))
                ->merge($commandFilter(MultiStepCommand::class))
                ->merge(Collection::create([new ComposerGlobalMultiInstallCommand($commandFilter(ComposerGlobalInstallCommand::class))]))
                ->merge(Collection::create([new OptimisedComposerBinPluginCommand($commandFilter(ComposerBinPluginCommand::class))]))
                ->merge($commandFilter(ComposerInstallCommand::class))
                ->merge($commandFilter(BoxBuildCommand::class))
        );
    }

    private function commandFilter(): \Closure
    {
        return function ($type) {
            return $this->commands()->filter(function (Command $command) use ($type) {
                return $command instanceof $type;
            });
        };
    }

    private function commands(): Collection
    {
        return $this->tools->all()->map(function (Tool $tool) {
            return $tool->command();
        });
    }
}
