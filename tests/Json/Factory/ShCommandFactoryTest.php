<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\ShCommandFactory;
use Zalas\Toolbox\Tool\Command\ShCommand;

class ShCommandFactoryTest extends TestCase
{
    public function test_creates_a_command()
    {
        $command = ShCommandFactory::import([
            'command' => 'echo "42"',
        ]);

        $this->assertInstanceOf(ShCommand::class, $command);
    }

    public function test_it_complains_if_command_is_missing()
    {
        $this->expectException(\InvalidArgumentException::class);

        ShCommandFactory::import([]);
    }
}
