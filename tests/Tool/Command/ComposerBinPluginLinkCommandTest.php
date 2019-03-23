<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginLinkCommand;

final class ComposerBinPluginLinkCommandTest extends TestCase
{
    private const SOURCE = 'churn';
    private const TARGET = '/tools/churn';
    private const NAMESPACE = 'tools';

    /**
     * @var ComposerBinPluginLinkCommand
     */
    private $command;

    protected function setUp()
    {
        $this->command = new ComposerBinPluginLinkCommand(self::SOURCE, self::TARGET, self::NAMESPACE);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_a_symlink_command()
    {
        $this->assertRegExp('#ln -sf \$\{COMPOSER_HOME:-"~/.composer"\}/vendor-bin/tools/vendor/bin/churn /tools/churn#', (string) $this->command);
    }
}
