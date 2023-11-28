<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;
use Zalas\Toolbox\UseCase\TestTools;

class TestToolsTest extends TestCase
{
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
        return new Tool(
            "any name",
            "any summary",
            "https://example.com",
            [],
            new Command\ShCommand("any command"),
            $testCommand
        );
    }

    private function command(string $command): Command
    {
        return new ShCommand($command);
    }

    private function tools(array $testCommands, Filter $filter): Tools
    {
        $tools = $this->createStub(Tools::class);
        $tools->method('all')->with($filter)->willReturn(Collection::create(
            \array_map(fn ($command) => $this->tool($command), $testCommands)
        ));

        return $tools;
    }

    private function filter(): Filter
    {
        return new Filter([], []);
    }
}
