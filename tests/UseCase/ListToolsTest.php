<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\TestCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;
use Zalas\Toolbox\UseCase\ListTools;

class ListToolsTest extends TestCase
{
    public function test_it_returns_loaded_tools()
    {
        $tools = Collection::create([$this->anyTool(), $this->anyTool()]);
        $filter = $this->filter();

        $repository = $this->givenToolsFor($filter, $tools);

        $useCase = new ListTools($repository);

        $this->assertSame($tools, $useCase($filter));
    }

    private function filter(): Filter
    {
        return new Filter([], []);
    }

    private function anyTool(): Tool
    {
        return new Tool(
            "any name",
            "any summary",
            "https://example.com",
            [],
            new Command\ShCommand("any command"),
            new TestCommand("any test command", "any test name")
        );
    }

    private function givenToolsFor(Filter $filter, Collection $tools): Tools
    {
        $repository = $this->createStub(Tools::class);
        $repository->method('all')->with($filter)->willReturn($tools);
        return $repository;
    }
}
