<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;

class MultiStepCommandTest extends TestCase
{
    use ProphecyTrait;

    public function test_it_is_a_command()
    {
        $command = new MultiStepCommand(Collection::create([$this->command('echo "A"')]));

        $this->assertInstanceOf(Command::class, $command);
    }

    public function test_it_glues_all_its_subcommands()
    {
        $command1 = $this->command('echo "A"');
        $command2 = $this->command('echo "B"');

        $command = new MultiStepCommand(Collection::create([$command1, $command2]));

        $this->assertSame('echo "A" && echo "B"', (string) $command);
    }

    public function test_it_glues_all_its_subcommands_with_a_custom_glue()
    {
        $command1 = $this->command('echo "A"');
        $command2 = $this->command('echo "B"');

        $command = new MultiStepCommand(Collection::create([$command1, $command2]), ' ; ');

        $this->assertSame('echo "A" ; echo "B"', (string) $command);
    }

    public function test_it_throws_an_exception_if_there_is_no_steps()
    {
        $this->expectException(InvalidArgumentException::class);

        new MultiStepCommand(Collection::create([]));
    }

    private function command(string $commandString): Command
    {
        $command = $this->prophesize(Command::class);
        $command->__toString()->willReturn($commandString);

        return $command->reveal();
    }
}
