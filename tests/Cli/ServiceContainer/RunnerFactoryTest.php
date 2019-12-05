<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\ServiceContainer;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Cli\ServiceContainer\RunnerFactory;
use Zalas\Toolbox\Runner\ParametrisedRunner;
use Zalas\Toolbox\Runner\PassthruRunner;
use Zalas\Toolbox\Tool\Command;

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

    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->input = $this->prophesize(InputInterface::class);
        $this->output = $this->prophesize(OutputInterface::class);

        $this->container->get(InputInterface::class)->willReturn($this->input);
        $this->container->get(OutputInterface::class)->willReturn($this->output);
        $this->input->getOption('dry-run')->willReturn(false);
        $this->input->hasOption('target-dir')->willReturn(false);

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

    public function test_it_creates_the_parametrised_runner_if_target_dir_option_is_present()
    {
        $this->input->hasOption('target-dir')->willReturn(true);
        $this->input->getOption('target-dir')->willReturn('/usr/local/bin');

        $runner = $this->runnerFactory->createRunner();

        $this->assertInstanceOf(ParametrisedRunner::class, $runner);
    }

    public function test_the_parametrised_runner_includes_the_target_dir_parameter()
    {
        $this->input->hasOption('target-dir')->willReturn(true);
        $this->input->getOption('target-dir')->willReturn('/usr/local/bin');
        $this->input->getOption('dry-run')->willReturn(true);

        $runner = $this->runnerFactory->createRunner();

        $runner->run(new class implements Command {
            public function __toString(): string
            {
                return 'ls %target-dir%';
            }
        });

        $this->output->writeln('ls /usr/local/bin')->shouldHaveBeenCalled();
    }

    public function test_it_throws_an_exception_if_target_dir_does_not_exist()
    {
        $this->expectException(ContainerExceptionInterface::class);

        $this->input->hasOption('target-dir')->willReturn(true);
        $this->input->getOption('target-dir')->willReturn('/foo/bar/baz');

        $this->runnerFactory->createRunner();
    }

    public function test_it_uses_the_real_path_as_target_dir()
    {
        $this->input->hasOption('target-dir')->willReturn(true);
        $this->input->getOption('target-dir')->willReturn(__DIR__.'/../../../bin');
        $this->input->getOption('dry-run')->willReturn(true);

        $runner = $this->runnerFactory->createRunner();
        $runner->run(new class implements Command {
            public function __toString(): string
            {
                return 'ls %target-dir%';
            }
        });

        $this->output->writeln(\sprintf('ls %s', \realpath(__DIR__.'/../../../bin')))->shouldHaveBeenCalled();
    }
}
