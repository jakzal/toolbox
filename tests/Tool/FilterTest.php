<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;

class FilterTest extends TestCase
{
    use ProphecyTrait;

    public function test_it_returns_true_if_no_excluded_tags_were_defined()
    {
        $filter = new Filter([], []);

        $this->assertTrue($filter($this->tool(['phpspec', 'phpstan'])));
    }

    public function test_it_returns_true_if_no_excluded_tags_match()
    {
        $filter = new Filter(['exclude-php:7.3'], []);

        $this->assertTrue($filter($this->tool(['phpspec', 'phpstan'])));
    }

    public function test_it_returns_true_if_tool_has_no_tags()
    {
        $filter = new Filter(['exclude-php:7.3'], []);

        $this->assertTrue($filter($this->tool([])));
    }

    public function test_it_returns_true_if_neither_tool_nor_excluded_tags_were_defined()
    {
        $filter = new Filter([], []);

        $this->assertTrue($filter($this->tool([])));
    }

    public function test_it_returns_false_if_one_excluded_tag_matches()
    {
        $filter = new Filter(['exclude-php:7.3'], []);

        $this->assertFalse($filter($this->tool(['phpspec', 'phpstan', 'exclude-php:7.3'])));
    }

    public function test_it_returns_false_if_multiple_excluded_tags_match()
    {
        $filter = new Filter(['exclude-php:7.3', 'phpstan'], []);

        $this->assertFalse($filter($this->tool(['phpspec', 'phpstan', 'exclude-php:7.3'])));
    }

    public function test_it_returns_false_if_all_excluded_tags_match()
    {
        $filter = new Filter(['exclude-php:7.3', 'phpspec', 'phpstan'], []);

        $this->assertFalse($filter($this->tool(['phpspec', 'phpstan', 'exclude-php:7.3'])));
    }

    public function test_it_returns_true_if_a_tag_matches()
    {
        $filter = new Filter([], ['phpspec']);

        $this->assertTrue($filter($this->tool(['phpspec', 'phpstan'])));
    }

    public function test_it_returns_true_if_all_tags_match_exactly()
    {
        $filter = new Filter([], ['phpspec', 'phpstan']);

        $this->assertTrue($filter($this->tool(['phpspec', 'phpstan'])));
    }

    public function test_it_returns_true_if_all_tags_match()
    {
        $filter = new Filter([], ['phpspec', 'phpstan', 'foo', 'bar']);

        $this->assertTrue($filter($this->tool(['phpspec', 'phpstan'])));
    }

    public function test_it_returns_false_if_the_tool_has_no_tags_to_match()
    {
        $filter = new Filter([], ['phpspec']);

        $this->assertFalse($filter($this->tool(['phpstan'])));
    }

    public function test_it_returns_false_if_a_tag_is_both_included_and_excluded()
    {
        $filter = new Filter(['phpstan'], ['phpspec', 'phpstan']);

        $this->assertFalse($filter($this->tool(['phpspec', 'phpstan'])));
    }

    private function tool(array $tags): Tool
    {
        $tool = $this->prophesize(Tool::class);
        $tool->tags()->willReturn($tags);

        return $tool->reveal();
    }
}
