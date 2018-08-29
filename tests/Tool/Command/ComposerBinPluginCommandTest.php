<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;

class ComposerBinPluginCommandTest extends TestCase
{
    const PACKAGE = 'phpstan/phpstan';
    const NAMESPACE = 'tools';

    /**
     * @var ComposerBinPluginCommand
     */
    private $command;

    protected function setUp()
    {
        $this->command = ComposerBinPluginCommand::import([
            'package' => self::PACKAGE,
            'namespace' => self::NAMESPACE,
        ]);
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

    /**
     * @dataProvider provideRequiredProperties
     */
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'package' => self::PACKAGE,
            'namespace' => self::NAMESPACE,
        ];

        unset($properties[$property]);

        ComposerBinPluginCommand::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['package'];
        yield ['namespace'];
    }
}
