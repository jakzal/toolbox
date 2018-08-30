<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;
use Zalas\Toolbox\Tool\Command\ComposerGlobalMultiInstallCommand;

class ComposerGlobalMultiInstallCommandTest extends TestCase
{
    public function test_it_is_a_command()
    {
        $command = new ComposerGlobalMultiInstallCommand(Collection::create([
            new ComposerGlobalInstallCommand('phan/phan'),
            new ComposerGlobalInstallCommand('phpstan/phpstan'),
        ]));

        $this->assertInstanceOf(Command::class, $command);
    }

    public function test_it_generates_a_single_installation_command()
    {
        $command = new ComposerGlobalMultiInstallCommand(Collection::create([
            new ComposerGlobalInstallCommand('phan/phan'),
            new ComposerGlobalInstallCommand('phpstan/phpstan'),
        ]));

        $this->assertRegExp('#composer global require .*? phan/phan phpstan/phpstan#', (string) $command);
    }

    public function test_it_throws_an_exception_if_there_is_no_commands()
    {
        $this->expectException(InvalidArgumentException::class);

        new ComposerGlobalMultiInstallCommand(Collection::create([]));
    }
}
