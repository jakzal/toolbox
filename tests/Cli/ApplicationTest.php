<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Application as CliApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Application;
use Zalas\Toolbox\Cli\Command\InstallCommand;
use Zalas\Toolbox\Cli\Command\ListCommand;
use Zalas\Toolbox\Cli\ServiceContainer;

class ApplicationTest extends TestCase
{
    use ProphecyTrait;

    private const VERSION = 'test';

    /**
     * @var Application
     */
    private $app;

    /**
     * @var ServiceContainer|ObjectProphecy
     */
    private $container;

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ServiceContainer::class);
        $this->app = new Application(self::VERSION, $this->container->reveal());
    }

    public function test_it_is_a_cli_application()
    {
        $this->assertInstanceOf(CliApplication::class, $this->app);
    }

    public function test_it_defines_the_app_name_and_version()
    {
        $this->assertSame('toolbox', $this->app->getName());
        $this->assertSame(self::VERSION, $this->app->getVersion());
    }

    public function test_it_defines_tools_option()
    {
        $this->assertTrue($this->app->getDefinition()->hasOption('tools'));
        $this->assertEquals(
            [
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/pre-installation.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/architecture.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/checkstyle.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/compatibility.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/composer.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/deprecation.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/documentation.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/linting.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/metrics.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/phpstan.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/psalm.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/refactoring.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/security.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/test.json',
                \realpath(__DIR__.'/../../src/Cli/').'/../../resources/tools.json'
            ],
            $this->app->getDefinition()->getOption('tools')->getDefault()
        );
    }

    /**
     * @putenv TOOLBOX_JSON=resources/pre.json,resources/tools.json
     */
    public function test_it_takes_the_tools_option_default_from_environment_if_present()
    {
        $this->assertSame(['resources/pre.json', 'resources/tools.json'], $this->app->getDefinition()->getOption('tools')->getDefault());
    }

    /**
     * @putenv TOOLBOX_JSON=resources/pre.json , resources/tools.json
     */
    public function test_it_trims_the_tools_option()
    {
        $this->assertSame(['resources/pre.json', 'resources/tools.json'], $this->app->getDefinition()->getOption('tools')->getDefault());
    }

    /**
     * @group integration
     */
    public function test_it_allows_to_override_tools_location()
    {
        $app = new Application(self::VERSION, new ServiceContainer());
        $result = $app->doRun(
            new ArrayInput([
                'command' => ListCommand::NAME,
                '--tools' => [__DIR__.'/../resources/tools.json'],
                '--no-interaction' => true,
            ]),
            new NullOutput()
        );

        $this->assertSame(0, $result);
    }

    /**
     * @group integration
     */
    public function test_it_runs_the_command_in_dry_run_mode()
    {
        $output = $this->prophesize(OutputInterface::class);

        $app = new Application(self::VERSION, new ServiceContainer());
        $app->doRun(
            new ArrayInput([
                'command' => InstallCommand::NAME,
                '--dry-run' => true,
                '--tools' => [__DIR__.'/../resources/tools.json'],
                '--no-interaction' => true,
            ]),
            $output->reveal()
        );

        $output->writeln(Argument::allOf(
            Argument::type('string'),
            Argument::containingString('composer global bin phpstan require')
        ))->shouldHaveBeenCalled();
    }
}
