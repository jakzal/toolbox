<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Runner;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

class DryRunnerTest extends TestCase
{
    /**
     * @var DryRunner
     */
    private $runner;

    /**
     * @var OutputInterface|MockObject
     */
    private $out;

    protected function setUp(): void
    {
        $this->out = $this->createMock(OutputInterface::class);
        $this->runner = new DryRunner($this->out);
    }

    public function test_it_is_a_runner()
    {
        $this->assertInstanceOf(Runner::class, $this->runner);
    }

    public function test_it_sends_the_command_to_the_output()
    {
        $this->out->expects(self::once())
            ->method('writeln')
            ->with('echo "Foo"');

        $result = $this->runner->run(new class implements Command {
            public function __toString(): string
            {
                return 'echo "Foo"';
            }
        });

        $this->assertSame(0, $result);
    }
}
