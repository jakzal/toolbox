<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\PhpVersionsParser;

class PhpVersionsParserTest extends TestCase
{
    public function test_it_parses_tilde_constraints()
    {
        $this->assertSame(['8.2', '8.3', '8.4'], PhpVersionsParser::parse('~8.2.0 || ~8.3.0 || ~8.4.0'));
    }

    public function test_it_parses_caret_constraints()
    {
        $this->assertSame(['8.2', '8.3'], PhpVersionsParser::parse('^8.2 || ^8.3'));
    }

    public function test_it_parses_comparison_constraints()
    {
        $this->assertSame(['8.2'], PhpVersionsParser::parse('>=8.2.0'));
    }

    public function test_it_parses_mixed_constraint_formats()
    {
        $this->assertSame(['8.3', '8.4', '8.5'], PhpVersionsParser::parse('~8.3.0 || ^8.4 || >=8.5.0'));
    }

    public function test_it_sorts_versions()
    {
        $this->assertSame(['8.3', '8.4', '8.5'], PhpVersionsParser::parse('~8.5.0 || ~8.3.0 || ~8.4.0'));
    }

    public function test_it_removes_duplicate_versions()
    {
        $this->assertSame(['8.2'], PhpVersionsParser::parse('~8.2.0 || ^8.2 || >=8.2.0'));
    }
}
