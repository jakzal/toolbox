<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\ServiceContainer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Cli\ServiceContainer\RunnerFactory;
use Zalas\Toolbox\Runner\ParametrisedRunner;
use Zalas\Toolbox\Runner\PassthruRunner;
use Zalas\Toolbox\Tool\Command;

class RunnerFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var RunnerFactory
     */
    private $runnerFactory;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface|MockObject
     */
    private $output;

    protected function setUp(): void
    {
        $this->input = $this->givenInput([]);
        $this->output = $this->createStub(OutputInterface::class);

        $this->container = new class([ InputInterface::class => &$this->input, OutputInterface::class => &$this->output, ]) implements ContainerInterface {

            public function __construct(private array $services)
            {
            }

            public function get(string $id)
            {
                return $this->services[$id];
            }

            public function has(string $id): bool
            {
                return isset($this->services[$id]);
            }
        };

        $this->runnerFactory = new RunnerFactory($this->container);
    }

    public function test_it_creates_the_passthru_runner_by_default()
    {
        $runner = $this->runnerFactory->createRunner();

        $this->assertInstanceOf(PassthruRunner::class, $runner);
    }

    public function test_it_creates_the_dry_runner_if_dry_run_option_is_passed()
    {
        $this->givenInput(['--dry-run' => true]);

        $runner = $this->runnerFactory->createRunner();

        $this->assertInstanceOf(DryRunner::class, $runner);
    }

    public function test_it_creates_the_parametrised_runner_if_target_dir_option_is_present()
    {
        $this->givenInput(['--target-dir' => '/usr/local/bin']);

        $runner = $this->runnerFactory->createRunner();

        $this->assertInstanceOf(ParametrisedRunner::class, $runner);
    }

    public function test_the_parametrised_runner_includes_the_target_dir_parameter()
    {
        $this->givenInput(['--target-dir' => '/usr/local/bin', '--dry-run' => true]);

        $this->output->expects(self::once())->method('writeln')->with('ls /usr/local/bin');

        $runner = $this->runnerFactory->createRunner();

        $runner->run(new class implements Command {
            public function __toString(): string
            {
                return 'ls %target-dir%';
            }
        });
    }

    public function test_it_throws_an_exception_if_target_dir_does_not_exist()
    {
        $this->expectException(ContainerExceptionInterface::class);

        $this->givenInput(['--target-dir' => '/foo/bar/baz']);

        $this->runnerFactory->createRunner();
    }

    public function test_it_uses_the_real_path_as_target_dir()
    {
        $this->givenInput(['--target-dir' => __DIR__.'/../../../bin', '--dry-run' => true]);

        $this->output->expects(self::once())->method('writeln')->with(\sprintf('ls %s', \realpath(__DIR__.'/../../../bin')));

        $runner = $this->runnerFactory->createRunner();
        $runner->run(new class implements Command {
            public function __toString(): string
            {
                return 'ls %target-dir%';
            }
        });
    }

    private function givenInput(array $parameters): InputInterface
    {
        $this->input = new ArrayInput($parameters, new InputDefinition(\array_filter([
            new InputOption('dry-run', null, InputOption::VALUE_NONE),
            isset($parameters['--target-dir']) ? new InputOption('target-dir', null, InputOption::VALUE_REQUIRED) : null,
        ])));

        return $this->input;
    }
}
