<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;

class ComposerBinPluginCommandTest extends TestCase
{
    private const PACKAGE = 'phpstan/phpstan';
    private const NAMESPACE = 'tools';

    /**
     * @var ComposerBinPluginCommand
     */
    private $command;

    protected function setUp()
    {
        $this->command = new ComposerBinPluginCommand(self::PACKAGE, self::NAMESPACE);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertRegExp('#composer global bin tools require .*? phpstan/phpstan#', (string) $this->command);
    }

    public function test_it_exposes_the_package_and_namespace()
    {
        $this->assertSame(self::PACKAGE, $this->command->package());
        $this->assertSame(self::NAMESPACE, $this->command->namespace());
    }
}
