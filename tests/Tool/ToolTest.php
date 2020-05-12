<?php
declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool;

use PHPUnit\Framework\TestCase;
use TypeError;
use Zalas\Toolbox\Tests\Prophecy\Prophecy;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Tool;

class ToolTest extends TestCase
{
    use Prophecy;

    public function test_it_exposes_its_properties()
    {
        $command = $this->prophesize(Command::class)->reveal();
        $testCommand = $this->prophesize(Command::class)->reveal();

        $tool = new Tool('phpstan', 'Static analysis tool', 'https://github.com/phpstan/phpstan', ['qa', 'static-analysis'], $command, $testCommand);

        $this->assertSame('phpstan', $tool->name());
        $this->assertSame('Static analysis tool', $tool->summary());
        $this->assertSame('https://github.com/phpstan/phpstan', $tool->website());
        $this->assertSame(['qa', 'static-analysis'], $tool->tags());
        $this->assertSame($command, $tool->command());
        $this->assertSame($testCommand, $tool->testCommand());
    }

    public function test_tags_can_only_be_strings()
    {
        $this->expectException(TypeError::class);
        $command = $this->prophesize(Command::class)->reveal();
        $testCommand = $this->prophesize(Command::class)->reveal();

        new Tool('phpstan', 'Static analysis tool', 'https://github.com/phpstan/phpstan', [['qa'], ['static-analysis']], $command, $testCommand);
    }
}
