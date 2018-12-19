<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Zalas\Toolbox\Json\JsonTools;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tools;

class JsonToolsTest extends TestCase
{
    public function test_it_is_a_tools_repository()
    {
        $this->assertInstanceOf(Tools::class, new JsonTools($this->locator([__DIR__.'/../resources/tools.json'])));
    }

    public function test_it_throws_an_exception_if_resource_is_missing()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Could not read the file/');

        $tools = new JsonTools($this->locator(['/foo/tools.json']));
        $tools->all($this->filter());
    }

    public function test_it_throws_an_exception_if_resource_contains_invalid_json()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/Failed to parse json/');

        $tools = new JsonTools($this->locator([__DIR__.'/../resources/invalid.json']));
        $tools->all($this->filter());
    }

    public function test_it_throws_an_exception_if_tools_are_not_present_in_the_resource()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/Failed to find any tools/');

        $tools = new JsonTools($this->locator([__DIR__.'/../resources/no-tools.json']));
        $tools->all($this->filter());
    }

    public function test_it_throws_an_exception_if_tools_is_not_a_collection()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/Failed to find any tools/');

        $tools = new JsonTools($this->locator([__DIR__.'/../resources/invalid-tools.json']));
        $tools->all($this->filter());
    }

    public function test_it_loads_tools_from_multiple_resources()
    {
        $tools = \iterator_to_array(
            (new JsonTools($this->locator([
                __DIR__.'/../resources/pre-installation.json',
                __DIR__.'/../resources/tools.json',
            ])))->all($this->filter())
        );

        $this->assertCount(8, $tools);
        $this->assertSame('composer', $tools[0]->name());
        $this->assertSame('composer-bin-plugin', $tools[1]->name());
        $this->assertSame('box', $tools[2]->name());
        $this->assertSame('analyze', $tools[3]->name());
        $this->assertSame('behat', $tools[4]->name());
        $this->assertSame('deptrac', $tools[5]->name());
        $this->assertSame('infection', $tools[6]->name());
        $this->assertSame('phpstan', $tools[7]->name());
    }

    public function test_it_filters_out_tools()
    {
        $tools = \iterator_to_array(
            (new JsonTools($this->locator([__DIR__.'/../resources/tools.json'])))->all($this->filter(['static-analysis', 'testing']))
        );

        $this->assertCount(1, $tools);
        $this->assertSame('phpstan', $tools[0]->name());
    }

    private function locator(array $resources): callable
    {
        return function () use ($resources): array {
            return $resources;
        };
    }

    private function filter(array $excludedTags = []): Filter
    {
        return new Filter($excludedTags);
    }
}
