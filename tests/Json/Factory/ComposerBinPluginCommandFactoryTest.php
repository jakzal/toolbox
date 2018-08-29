<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\ComposerBinPluginCommandFactory;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;

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

        ComposerBinPluginCommandFactory::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['package'];
        yield ['namespace'];
    }
}
