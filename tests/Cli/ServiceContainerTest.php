<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
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

    public function test_it_registers_a_runtime_service()
    {
        $service = $this->prophesize(InputInterface::class)->reveal();

        $this->container->set(InputInterface::class, $service);

        $this->assertTrue($this->container->has(InputInterface::class));
        $this->assertSame($service, $this->container->get(InputInterface::class));
    }

    public function test_it_returns_false_if_runtime_service_has_not_been_defined()
    {
        $this->assertFalse($this->container->has(InputInterface::class));
    }

    public function test_it_throws_an_exception_if_missing_runtime_service_is_accessed()
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $this->container->get(InputInterface::class);
    }

    public function test_it_throws_an_exception_if_unknown_runtime_service_is_provided()
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage('The "foo" runtime service is not expected.');

        $this->container->set('foo', new \stdClass());
    }
}
