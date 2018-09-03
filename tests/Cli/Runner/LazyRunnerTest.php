<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Runner;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Cli\Runner\LazyRunner;
use Zalas\Toolbox\Runner\PassthruRunner;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

class LazyRunnerTest extends TestCase
{
    /**
     * @var LazyRunner
     */
    private $runner;

    /**
     * @var ContainerInterface|ObjectProphecy
     */
    private $container;

    /**
     * @var InputInterface|ObjectProphecy
     */
    private $input;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->input = $this->prophesize(InputInterface::class);

        $this->container->get(InputInterface::class)->willReturn($this->input);
        $this->container->has(InputInterface::class)->willReturn(true);
        $this->container->has(PassthruRunner::class)->willReturn(true);
        $this->container->has(DryRunner::class)->willReturn(true);

        $this->runner = new LazyRunner($this->container->reveal());
    }

    public function test_it_is_a_runner()
    {
        $this->assertInstanceOf(Runner::class, $this->runner);
    }

    public function test_it_runs_dry_runner_if_dry_run_option_is_present()
    {
        $command = $this->command();

        $runner = $this->prophesize(Runner::class);
        $runner->run($command)->willReturn(0);

        $this->input->getOption('dry-run')->willReturn(true);
        $this->container->get(DryRunner::class)->willReturn($runner);

        $this->runner->run($command);

        $runner->run($command)->shouldHaveBeenCalled();
    }

    public function test_it_returns_status_code_of_the_real_runner()
    {
        $command = $this->command();

        $runner = $this->prophesize(Runner::class);
        $runner->run($command)->willReturn(1);

        $this->input->getOption('dry-run')->willReturn(true);
        $this->container->get(DryRunner::class)->willReturn($runner);

        $this->assertSame(1, $this->runner->run($command));
    }

    public function test_it_runs_passthru_runner_if_dry_run_option_is_absent()
    {
        $command = $this->command();

        $runner = $this->prophesize(Runner::class);
        $runner->run($command)->willReturn(0);

        $this->input->getOption('dry-run')->willReturn(false);
        $this->container->get(PassthruRunner::class)->willReturn($runner);

        $this->runner->run($command);

        $runner->run($command)->shouldHaveBeenCalled();
    }

    public function test_it_only_initializes_the_runner_once()
    {
        $command = $this->command();

        $runner = $this->prophesize(Runner::class);
        $runner->run($command)->willReturn(0);

        $this->input->getOption('dry-run')->willReturn(false);
        $this->container->get(PassthruRunner::class)->willReturn($runner);

        $this->runner->run($command);
        $this->runner->run($command);

        $this->container->get(PassthruRunner::class)->shouldHaveBeenCalledTimes(1);
    }

    /**
     * @dataProvider provideRequiredServices
     */
    public function test_it_complains_if_any_of_services_it_needs_from_the_container_are_not_defined(string $serviceId)
    {
        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage(\sprintf('The service "%s" is missing.', $serviceId));

        $this->container->has($serviceId)->willReturn(false);
        $this->container->get($serviceId)->willThrow(new class extends \RuntimeException implements NotFoundExceptionInterface {
        });

        new LazyRunner($this->container->reveal());
    }

    public function provideRequiredServices()
    {
        yield [InputInterface::class];
        yield [PassthruRunner::class];
        yield [DryRunner::class];
    }

    private function command(): Command
    {
        return $this->prophesize(Command::class)->reveal();
    }
}
