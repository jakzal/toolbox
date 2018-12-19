<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;

class FilterTest extends TestCase
{
    public function test_it_returns_true_if_no_excluded_tags_were_defined()
    {
        $filter = new Filter([]);

        $this->assertTrue($filter($this->tool(['phpspec', 'phpstan'])));
    }

    public function test_it_returns_true_if_no_excluded_tags_match()
    {
        $filter = new Filter(['~php:7.3']);

        $this->assertTrue($filter($this->tool(['phpspec', 'phpstan'])));
    }

    public function test_it_returns_true_if_tool_has_no_tags()
    {
        $filter = new Filter(['~php:7.3']);

        $this->assertTrue($filter($this->tool([])));
    }

    public function test_it_returns_true_if_neither_tool_nor_excluded_tags_were_defined()
    {
        $filter = new Filter([]);

        $this->assertTrue($filter($this->tool([])));
    }

    public function test_it_returns_false_if_one_excluded_tag_matches()
    {
        $filter = new Filter(['~php:7.3']);

        $this->assertFalse($filter($this->tool(['phpspec', 'phpstan', '~php:7.3'])));
    }

    public function test_it_returns_false_if_multiple_excluded_tags_match()
    {
        $filter = new Filter(['~php:7.3', 'phpstan']);

        $this->assertFalse($filter($this->tool(['phpspec', 'phpstan', '~php:7.3'])));
    }

    public function test_it_returns_false_if_all_excluded_tags_match()
    {
        $filter = new Filter(['~php:7.3', 'phpspec', 'phpstan']);

        $this->assertFalse($filter($this->tool(['phpspec', 'phpstan', '~php:7.3'])));
    }

    private function tool(array $tags): Tool
    {
        $tool = $this->prophesize(Tool::class);
        $tool->tags()->willReturn($tags);

        return $tool->reveal();
    }
}
