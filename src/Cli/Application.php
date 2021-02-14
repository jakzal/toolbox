<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application as CliApplication;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Command\InstallCommand;
use Zalas\Toolbox\Cli\Command\ListCommand;
use Zalas\Toolbox\Cli\Command\TestCommand;

final class Application extends CliApplication
{
    private $serviceContainer;

    public function __construct(string $version, ServiceContainer $serviceContainer)
    {
        parent::__construct('toolbox', $version);

        $this->serviceContainer = $serviceContainer;

        $this->setCommandLoader($this->createCommandLoader($serviceContainer));
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->serviceContainer->set(InputInterface::class, $input);
        $this->serviceContainer->set(OutputInterface::class, $output);

        return parent::doRun($input, $output);
    }

    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOption(new InputOption('tools', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Path(s) to the list of tools. Can also be set with TOOLBOX_JSON environment variable.', $this->toolsJsonDefault()));

        return $definition;
    }

    private function toolsJsonDefault(): array
    {
        return \getenv('TOOLBOX_JSON')
            ? \array_map('trim', \explode(',', \getenv('TOOLBOX_JSON')))
            : [
                __DIR__.'/../../resources/pre-installation.json',
                __DIR__.'/../../resources/architecture.json',
                __DIR__.'/../../resources/checkstyle.json',
                __DIR__.'/../../resources/compatibility.json',
                __DIR__.'/../../resources/composer.json',
                __DIR__.'/../../resources/deprecation.json',
                __DIR__.'/../../resources/documentation.json',
                __DIR__.'/../../resources/linting.json',
                __DIR__.'/../../resources/metrics.json',
                __DIR__.'/../../resources/phpcs.json',
                __DIR__.'/../../resources/phpstan.json',
                __DIR__.'/../../resources/psalm.json',
                __DIR__.'/../../resources/refactoring.json',
                __DIR__.'/../../resources/security.json',
                __DIR__.'/../../resources/test.json',
                __DIR__.'/../../resources/tools.json',
            ];
    }

    private function createCommandLoader(ContainerInterface $container): CommandLoaderInterface
    {
        return new ContainerCommandLoader(
            $container,
            [
                InstallCommand::NAME => InstallCommand::class,
                ListCommand::NAME => ListCommand::class,
                TestCommand::NAME => TestCommand::class,
            ]
        );
    }
}
