<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;
use Zalas\Toolbox\UseCase\TestTools;

class TestToolsTest extends TestCase
{
    use ProphecyTrait;

    public function test_it_returns_test_aggregated_test_command()
    {
        $testCommands = [
            $this->command('echo "a"'),
            $this->command('echo "b"'),
        ];
        $filter = $this->filter();
        $tools = $this->tools($testCommands, $filter);

        $useCase = new TestTools($tools);

        $this->assertSame('echo "a" && echo "b"', (string) $useCase($filter));
    }

    private function tool(Command $testCommand): Tool
    {
        $tool = $this->prophesize(Tool::class);
        $tool->testCommand()->willReturn($testCommand);
        $tool->tags()->willReturn([]);

        return $tool->reveal();
    }

    private function command(string $command): Command
    {
        $c = $this->prophesize(Command::class);
        $c->__toString()->willReturn($command);

        return $c->reveal();
    }

    private function tools(array $testCommands, Filter $filter): Tools
    {
        $tools = $this->prophesize(Tools::class);
        $tools->all($filter)->willReturn(Collection::create([
            $this->tool($testCommands[0]),
            $this->tool($testCommands[1]),
        ]));

        return $tools->reveal();
    }

    private function filter(): Filter
    {
        return new Filter([], []);
    }
}
