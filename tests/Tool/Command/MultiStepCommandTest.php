<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;

class MultiStepCommandTest extends TestCase
{
    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, new MultiStepCommand(Collection::create([])));
    }

    public function test_it_glues_all_its_subcommands()
    {
        $command1 = $this->prophesize(Command::class);
        $command2 = $this->prophesize(Command::class);

        $command1->__toString()->willReturn('echo "A"');
        $command2->__toString()->willReturn('echo "B"');

        $command = new MultiStepCommand(Collection::create([$command1->reveal(), $command2->reveal()]));

        $this->assertSame('echo "A" && echo "B"', (string) $command);
    }

    public function test_it_glues_all_its_subcommands_with_a_custom_glue()
    {
        $command1 = $this->prophesize(Command::class);
        $command2 = $this->prophesize(Command::class);

        $command1->__toString()->willReturn('echo "A"');
        $command2->__toString()->willReturn('echo "B"');

        $command = new MultiStepCommand(Collection::create([$command1->reveal(), $command2->reveal()]), ' ; ');

        $this->assertSame('echo "A" ; echo "B"', (string) $command);
    }
}
