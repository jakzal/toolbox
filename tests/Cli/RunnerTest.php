<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Cli\Runner;
use Zalas\Toolbox\Tool\Command\ShCommand;

class RunnerTest extends TestCase
{
    public function test_it_returns_the_exit_code_of_the_run_command()
    {
        $runner = new Runner();
        $this->assertSame(0, $runner->run(new ShCommand('true')));
        $this->assertSame(1, $runner->run(new ShCommand('false')));
    }

    public function test_it_outputs_commands_output()
    {
        $runner = new Runner();

        \ob_start();
        $runner->run(new ShCommand('echo "ABC"'));

        $this->assertSame('ABC'.PHP_EOL, \ob_get_clean());
    }
}
