<?php
declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Tool;

class ToolTest extends TestCase
{
    public function test_it_exposes_its_properties()
    {
        $command = $this->prophesize(Command::class)->reveal();
        $testCommand = $this->prophesize(Command::class)->reveal();

        $tool = new Tool('phpstan', 'Static analysis tool', 'https://github.com/phpstan/phpstan', $command, $testCommand);

        $this->assertSame('phpstan', $tool->name());
        $this->assertSame('Static analysis tool', $tool->summary());
        $this->assertSame('https://github.com/phpstan/phpstan', $tool->website());
        $this->assertSame($command, $tool->command());
        $this->assertSame($testCommand, $tool->testCommand());
    }
}
