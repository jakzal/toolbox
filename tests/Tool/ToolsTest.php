<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Zalas\Toolbox\Tool\Tools;

class ToolsTest extends TestCase
{
    public function test_it_throws_an_exception_if_resource_is_missing()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageRegExp('/Could not read the file/');

        new Tools('/foo/tools.json');
    }

    public function test_it_throws_an_exception_if_resource_contains_invalid_json()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/Failed to parse json/');

        $tools = new Tools(__DIR__.'/../resources/invalid.json');
        $tools->all();
    }

    public function test_it_throws_an_exception_if_tools_are_not_present_in_the_resource()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/Failed to find any tools/');

        $tools = new Tools(__DIR__.'/../resources/no-tools.json');
        $tools->all();
    }

    public function test_it_throws_an_exception_if_tools_is_not_a_collection()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageRegExp('/Failed to find any tools/');

        $tools = new Tools(__DIR__.'/../resources/invalid-tools.json');
        $tools->all();
    }

    public function test_it_loads_tools_from_the_resource_with_default_ones_prepended()
    {
        $tools = \iterator_to_array(
            (new Tools(__DIR__.'/../resources/tools.json'))->all()
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
}
