<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ShCommand;

class ShCommandTest extends TestCase
{
    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, new ShCommand('echo'));
    }

    public function test_it_returns_the_command()
    {
        $this->assertSame('echo "A"', (string) new ShCommand('echo "A"'));
    }
}
