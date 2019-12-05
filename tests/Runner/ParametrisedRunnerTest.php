<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Runner;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Toolbox\Runner\ParametrisedRunner;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

class ParametrisedRunnerTest extends TestCase
{
    /**
     * @var ParametrisedRunner
     */
    private $runner;

    /**
     * @var Runner|ObjectProphecy
     */
    private $decoratedRunner;

    protected function setUp(): void
    {
        $this->decoratedRunner = $this->prophesize(Runner::class);
        $this->runner = new ParametrisedRunner($this->decoratedRunner->reveal(), ['%foo%' => 'ABC']);
    }

    public function test_it_is_a_runner()
    {
        $this->assertInstanceOf(Runner::class, $this->runner);
    }

    public function test_it_replaces_parameter_holders_in_the_command_before_running_it()
    {
        $command = $this->command('echo "%foo%"');

        $this->decoratedRunner->run(Argument::that(function (Command $c) {
            if ('echo "ABC"' !== $c->__toString()) {
                throw new \RuntimeException(\sprintf('Expected `echo "ABC"`, but got `%s`.', $c->__toString()));
            }

            return true;
        }))->willReturn(42);

        $exitCode = $this->runner->run($command);

        $this->assertSame(42, $exitCode);
    }

    private function command(string $commandString): Command
    {
        $command = $this->prophesize(Command::class);
        $command->__toString()->willReturn($commandString);

        return $command->reveal();
    }
}
