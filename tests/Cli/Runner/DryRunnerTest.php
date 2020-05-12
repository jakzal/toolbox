<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Runner;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tests\Prophecy\Prophecy;
use Zalas\Toolbox\Tool\Command;

class DryRunnerTest extends TestCase
{
    use Prophecy;

    /**
     * @var DryRunner
     */
    private $runner;

    /**
     * @var OutputInterface|ObjectProphecy
     */
    private $out;

    protected function setUp(): void
    {
        $this->out = $this->prophesize(OutputInterface::class);
        $this->runner = new DryRunner($this->out->reveal());
    }

    public function test_it_is_a_runner()
    {
        $this->assertInstanceOf(Runner::class, $this->runner);
    }

    public function test_it_sends_the_command_to_the_output()
    {
        $result = $this->runner->run(new class implements Command {
            public function __toString(): string
            {
                return 'echo "Foo"';
            }
        });

        $this->out->writeln('echo "Foo"')->shouldHaveBeenCalled();

        $this->assertSame(0, $result);
    }
}
