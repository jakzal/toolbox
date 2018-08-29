<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zalas\Toolbox\Cli\Command\InstallCommand;
use Zalas\Toolbox\Cli\Command\ListCommand;
use Zalas\Toolbox\Cli\Command\TestCommand;
use Zalas\Toolbox\Cli\ServiceContainer;

class ServiceContainerTest extends TestCase
{
    /**
     * @var ServiceContainer
     */
    private $container;

    protected function setUp()
    {
        $this->container = new ServiceContainer();
        $this->container->setParameter('toolbox_json', __DIR__.'/../resources/tools.json');
    }

    public function test_it_is_a_psr_container()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->container);
    }

    public function test_it_returns_false_if_service_is_not_registered()
    {
        $this->assertFalse($this->container->has('foo'));
    }

    public function test_it_throws_an_exception_if_unregistered_service_is_accessed()
    {
        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage('The "foo" service is not registered in the service container.');

        $this->container->get('foo');
    }

    public function test_it_throws_an_exception_if_toolbox_json_parameter_is_missing()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('The "toolbox_json" parameter is not defined.');

        $this->container = new ServiceContainer();
        $this->container->get(InstallCommand::class);
    }

    public function test_it_creates_the_install_command()
    {
        $this->assertTrue($this->container->has(InstallCommand::class));
        $this->assertInstanceOf(InstallCommand::class, $this->container->get(InstallCommand::class));
    }

    public function test_it_creates_the_list_command()
    {
        $this->assertTrue($this->container->has(ListCommand::class));
        $this->assertInstanceOf(ListCommand::class, $this->container->get(ListCommand::class));
    }

    public function test_it_creates_the_test_command()
    {
        $this->assertTrue($this->container->has(TestCommand::class));
        $this->assertInstanceOf(TestCommand::class, $this->container->get(TestCommand::class));
    }
}
