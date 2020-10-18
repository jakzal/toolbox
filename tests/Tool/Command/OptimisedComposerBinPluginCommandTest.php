<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginLinkCommand;
use Zalas\Toolbox\Tool\Command\OptimisedComposerBinPluginCommand;

class OptimisedComposerBinPluginCommandTest extends TestCase
{
    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, new OptimisedComposerBinPluginCommand(Collection::create([new ComposerBinPluginCommand('phpstan/phpstan', 'phpstan', Collection::create([]))])));
    }

    public function test_it_groups_composer_bin_command_by_namespace()
    {
        $commands = [
            new ComposerBinPluginCommand('phpstan/phpstan', 'phpstan', Collection::create([])),
            new ComposerBinPluginCommand('phan/phan', 'tools', Collection::create([])),
            new ComposerBinPluginCommand('behat/behat', 'tools', Collection::create([])),
        ];

        $command = new OptimisedComposerBinPluginCommand(Collection::create($commands));

        $this->assertMatchesRegularExpression('#composer global bin phpstan require .*? phpstan/phpstan && composer global bin tools require .*? phan/phan behat/behat#', (string) $command);
    }

    public function test_it_throws_an_exception_if_there_is_no_commands()
    {
        $this->expectException(InvalidArgumentException::class);

        new OptimisedComposerBinPluginCommand(Collection::create([]));
    }

    public function test_it_creates_links_to_composer_bin_commands()
    {
        $commands = [
            new ComposerBinPluginCommand(
                'phpstan/phpstan',
                'phpstan',
                Collection::create([
                    new ComposerBinPluginLinkCommand('phpstan', '/tools/phpstan', 'phpstan'),
                    new ComposerBinPluginLinkCommand('phpstan', '/other/path/phpstan', 'phpstan'),
                ])
            ),
            new ComposerBinPluginCommand(
                'phan/phan',
                'tools',
                Collection::create([
                    new ComposerBinPluginLinkCommand('phan', '/tools/phan', 'tools'),
                ])
            ),
            new ComposerBinPluginCommand(
                'behat/behat',
                'tools',
                Collection::create([
                    new ComposerBinPluginLinkCommand('behat', '/tools/behat', 'tools'),
                ])
            ),
        ];

        $command = new OptimisedComposerBinPluginCommand(Collection::create($commands));

        $this->assertMatchesRegularExpression('#composer global bin phpstan require .*? phpstan/phpstan && composer global bin tools require .*? phan/phan behat/behat#', (string) $command);
        $this->assertMatchesRegularExpression('# && ln -sf.*?phpstan /tools/phpstan#', (string) $command);
        $this->assertMatchesRegularExpression('# && ln -sf.*?phpstan /other/path/phpstan#', (string) $command);
        $this->assertMatchesRegularExpression('# && ln -sf.*?phan /tools/phan#', (string) $command);
        $this->assertMatchesRegularExpression('# && ln -sf.*?behat /tools/behat#', (string) $command);
        $this->assertDoesNotMatchRegularExpression('#&&\s*&&#', (string) $command, 'It does not generate empty commands');
    }

    public function test_it_does_not_create_links_if_commands_have_no_links_defined()
    {
        $commands = [
            new ComposerBinPluginCommand('phpstan/phpstan', 'phpstan', Collection::create([])),
            new ComposerBinPluginCommand('phan/phan', 'tools', Collection::create([])),
            new ComposerBinPluginCommand('behat/behat', 'tools', Collection::create([])),
        ];

        $command = new OptimisedComposerBinPluginCommand(Collection::create($commands));

        $this->assertDoesNotMatchRegularExpression('#ln -s#', (string) $command);
        $this->assertDoesNotMatchRegularExpression('#&&\s*&&#', (string) $command, 'It does not generate empty commands');
    }
}
