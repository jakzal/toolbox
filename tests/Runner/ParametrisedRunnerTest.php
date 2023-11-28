<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Runner;

use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Runner\ParametrisedRunner;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ShCommand;

class ParametrisedRunnerTest extends TestCase
{
    /**
     * @var ParametrisedRunner
     */
    private $runner;

    /**
     * @var Runner|Stub
     */
    private $decoratedRunner;

    protected function setUp(): void
    {
        $this->decoratedRunner = $this->createStub(Runner::class);
        $this->runner = new ParametrisedRunner($this->decoratedRunner, ['%foo%' => 'ABC']);
    }

    public function test_it_is_a_runner()
    {
        $this->assertInstanceOf(Runner::class, $this->runner);
    }

    public function test_it_replaces_parameter_holders_in_the_command_before_running_it()
    {
        $command = $this->command('echo "%foo%"');

        $this->decoratedRunner->method('run')
            ->with(self::callback(function (Command $c) {
                if ('echo "ABC"' !== $c->__toString()) {
                    throw new \RuntimeException(\sprintf('Expected `echo "ABC"`, but got `%s`.', $c->__toString()));
                }

                return true;
            }))
            ->willReturn(42);

        $exitCode = $this->runner->run($command);

        $this->assertSame(42, $exitCode);
    }

    private function command(string $commandString): Command
    {
        return new ShCommand($commandString);
    }
}
