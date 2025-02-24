<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginLinkCommand;

class ComposerBinPluginCommandTest extends TestCase
{
    private const PACKAGE = 'phpstan/phpstan';
    private const NAMESPACE = 'tools';

    private ComposerBinPluginCommand $command;

    protected function setUp(): void
    {
        $this->command = new ComposerBinPluginCommand(
            self::PACKAGE,
            self::NAMESPACE,
            Collection::create([])
        );
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertMatchesRegularExpression('#composer global bin tools require .*? phpstan/phpstan#', (string) $this->command);
    }

    public function test_it_exposes_the_package_and_namespace()
    {
        $this->assertSame(self::PACKAGE, $this->command->package());
        $this->assertSame(self::NAMESPACE, $this->command->namespace());
    }

    public function test_it_optionally_creates_a_symlink()
    {
        $links =  Collection::create([
            new ComposerBinPluginLinkCommand('phpstan', '/tools/phpstan', self::NAMESPACE)
        ]);
        $this->command = new ComposerBinPluginCommand(self::PACKAGE, self::NAMESPACE, $links);

        $this->assertSame($links, $this->command->links());
        $this->assertMatchesRegularExpression('#composer global bin tools require .*? phpstan/phpstan#', (string) $this->command);
        $this->assertMatchesRegularExpression('# && ln -sf.*?phpstan /tools/phpstan#', (string) $this->command);
    }

    public function test_it_does_not_create_a_symlink_if_links_option_was_not_given()
    {
        $this->assertDoesNotMatchRegularExpression('#ln -s#', (string) $this->command);
    }
}
