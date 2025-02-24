<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\ComposerBinPluginCommandFactory;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginLinkCommand;

class ComposerBinPluginCommandFactoryTest extends TestCase
{
    private const PACKAGE = 'phpstan/phpstan';
    private const NAMESPACE = 'tools';

    public function test_it_creates_a_command()
    {
        $command = ComposerBinPluginCommandFactory::import([
            'package' => self::PACKAGE,
            'namespace' => self::NAMESPACE,
        ]);

        $this->assertInstanceOf(ComposerBinPluginCommand::class, $command);
    }

    public function test_it_creates_a_command_with_links_in_tools()
    {
        $command = ComposerBinPluginCommandFactory::import([
            'package' => self::PACKAGE,
            'namespace' => self::NAMESPACE,
            'links' => ['/tools/phpstan' => 'phpstan'],
        ]);

        $this->assertInstanceOf(ComposerBinPluginCommand::class, $command);
        $this->assertEquals(
            Collection::create([
                new ComposerBinPluginLinkCommand('phpstan', '/tools/phpstan', self::NAMESPACE)
            ]),
            $command->links()
        );
    }

    #[DataProvider('provideRequiredProperties')]
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'package' => self::PACKAGE,
            'namespace' => self::NAMESPACE,
        ];

        unset($properties[$property]);

        ComposerBinPluginCommandFactory::import($properties);
    }

    public static function provideRequiredProperties(): \Generator
    {
        yield ['package'];
        yield ['namespace'];
    }
}
