<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\ServiceContainer;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Toolbox\Cli\ServiceContainer\LazyRunner;
use Zalas\Toolbox\Cli\ServiceContainer\RunnerFactory;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tests\Prophecy\Prophecy;
use Zalas\Toolbox\Tool\Command;

class LazyRunnerTest extends TestCase
{
    use Prophecy;

    /**
     * @var LazyRunner
     */
    private $runner;

    /**
     * @var RunnerFactory|ObjectProphecy
     */
    private $factory;

    protected function setUp(): void
    {
        $this->factory = $this->prophesize(RunnerFactory::class);

        $this->runner = new LazyRunner($this->factory->reveal());
    }

    public function test_it_is_a_runner()
    {
        $this->assertInstanceOf(Runner::class, $this->runner);
    }

    public function test_it_returns_status_code_of_returned_by_the_created_runner()
    {
        $command = $this->command();

        $runner = $this->prophesize(Runner::class);
        $runner->run($command)->willReturn(1);

        $this->factory->createRunner()->willReturn($runner);

        $this->assertSame(1, $this->runner->run($command));
    }

    public function test_it_only_initializes_the_runner_once()
    {
        $command = $this->command();

        $runner = $this->prophesize(Runner::class);
        $runner->run($command)->willReturn(0);

        $this->factory->createRunner()->willReturn($runner);

        $this->runner->run($command);
        $this->runner->run($command);

        $this->factory->createRunner()->shouldHaveBeenCalledTimes(1);
    }

    private function command(): Command
    {
        return $this->prophesize(Command::class)->reveal();
    }
}
