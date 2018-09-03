<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Application as CliApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Zalas\Toolbox\Cli\Application;
use Zalas\Toolbox\Cli\Command\ListCommand;
use Zalas\Toolbox\Cli\ServiceContainer;

class ApplicationTest extends TestCase
{
    private const VERSION = 'test';

    /**
     * @var Application
     */
    private $app;

    /**
     * @var ServiceContainer|ObjectProphecy
     */
    private $container;

    protected function setUp()
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
    }

    /**
     * @putenv TOOLBOX_JSON=resources/pre.json,resources/tools.json
     */
    public function test_it_takes_the_tools_option_default_from_environment_if_present()
    {
        $this->assertSame(['resources/pre.json', 'resources/tools.json'], $this->app->getDefinition()->getOption('tools')->getDefault());
    }

    /**
     * @group integration
     */
    public function test_it_allows_to_override_tools_location()
    {
        $container = new ServiceContainer();

        $app = new Application(self::VERSION, $container);
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
}
