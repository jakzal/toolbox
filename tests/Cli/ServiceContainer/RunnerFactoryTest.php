<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\ServiceContainer;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Cli\ServiceContainer\RunnerFactory;
use Zalas\Toolbox\Runner\PassthruRunner;

class RunnerFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface|ObjectProphecy
     */
    private $container;

    /**
     * @var RunnerFactory
     */
    private $runnerFactory;

    /**
     * @var InputInterface|ObjectProphecy
     */
    private $input;

    /**
     * @var OutputInterface|ObjectProphecy
     */
    private $output;

    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->input = $this->prophesize(InputInterface::class);
        $this->output = $this->prophesize(OutputInterface::class);

        $this->container->get(InputInterface::class)->willReturn($this->input);
        $this->container->get(OutputInterface::class)->willReturn($this->output);
        $this->input->getOption('dry-run')->willReturn(false);

        $this->runnerFactory = new RunnerFactory($this->container->reveal());
    }

    public function test_it_creates_the_passthru_runner_by_default()
    {
        $runner = $this->runnerFactory->createRunner();

        $this->assertInstanceOf(PassthruRunner::class, $runner);
    }

    public function test_it_creates_the_dry_runner_if_dry_run_option_is_passed()
    {
        $this->input->getOption('dry-run')->willReturn(true);

        $runner = $this->runnerFactory->createRunner();

        $this->assertInstanceOf(DryRunner::class, $runner);
    }
}
