<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\ServiceContainer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Cli\ServiceContainer\LazyRunner;
use Zalas\Toolbox\Cli\ServiceContainer\RunnerFactory;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

class LazyRunnerTest extends TestCase
{
    private LazyRunner $lazyRunner;

    private RunnerFactory|MockObject $factory;

    protected function setUp(): void
    {
        $this->factory = $this->createMock(RunnerFactory::class);

        $this->lazyRunner = new LazyRunner($this->factory);
    }

    public function test_it_is_a_runner()
    {
        $this->assertInstanceOf(Runner::class, $this->lazyRunner);
    }

    public function test_it_returns_status_code_of_returned_by_the_created_runner()
    {
        $command = $this->command();

        $runner = $this->givenRunner(command: $command, result: 1);
        $this->givenFactoryCreates($runner);

        $this->assertSame(1, $this->lazyRunner->run($command));
    }

    public function test_it_only_initializes_the_runner_once()
    {
        $command = $this->command();

        $runner = $this->givenRunner($command, 0);

        $this->factory
            ->expects(self::once())
            ->method('createRunner')
            ->willReturn($runner);

        $this->lazyRunner->run($command);
        $this->lazyRunner->run($command);
    }

    public function givenRunner(Command $command, int $result): Runner
    {
        $runner = $this->createStub(Runner::class);
        $runner->method('run')->with($command)->willReturn($result);

        return $runner;
    }

    private function command(): Command
    {
        return new Command\ShCommand('any command');
    }

    private function givenFactoryCreates(Runner $runner): void
    {
        $this->factory->method('createRunner')->willReturn($runner);
    }
}
