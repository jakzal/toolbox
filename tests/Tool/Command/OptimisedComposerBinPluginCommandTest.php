<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\OptimisedComposerBinPluginCommand;

class OptimisedComposerBinPluginCommandTest extends TestCase
{
    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, new OptimisedComposerBinPluginCommand(Collection::create([])));
    }

    public function test_it_groups_composer_bin_command_by_namespace()
    {
        $commands = [
            new ComposerBinPluginCommand('phpstan/phpstan', 'phpstan'),
            new ComposerBinPluginCommand('phan/phan', 'tools'),
            new ComposerBinPluginCommand('behat/behat', 'tools'),
        ];

        $command = new OptimisedComposerBinPluginCommand(Collection::create($commands));

        $this->assertRegExp('#composer global bin phpstan require .*? phpstan/phpstan && composer global bin tools require .*? phan/phan behat/behat#', (string) $command);
    }
}
