<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\TestCommand;

class TestCommandTest extends TestCase
{
    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, new TestCommand('/usr/bin/true', 'true'));
    }

    public function test_it_generates_the_command()
    {
        $this->assertRegExp(
            '#\(\(/usr/bin/true > /dev/null && echo -e .*?✔\.*?\) || \(echo -e .*?✘.*?" && false\)\)#',
            (string) new TestCommand('/usr/bin/true', 'true')
        );
    }
}
