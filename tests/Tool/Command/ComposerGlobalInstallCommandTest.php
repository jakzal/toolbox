<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;

class ComposerGlobalInstallCommandTest extends TestCase
{
    private const PACKAGE = 'phan/phan';

    private ComposerGlobalInstallCommand $command;

    protected function setUp(): void
    {
        $this->command = new ComposerGlobalInstallCommand(self::PACKAGE);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_exposes_the_package_name()
    {
        $this->assertSame(self::PACKAGE, $this->command->package());
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertMatchesRegularExpression('#composer global require .*? phan/phan#', (string) $this->command);
    }
}
