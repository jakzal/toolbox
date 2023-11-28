<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\ComposerInstallCommandFactory;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;

class ComposerInstallCommandFactoryTest extends TestCase
{
    private const REPOSITORY = 'https://github.com/behat/behat.git';
    private const LOCATION = '/tools';
    private const VERSION = 'v3.4.0';

    public function test_it_creates_a_command()
    {
        $command = ComposerInstallCommandFactory::import([
            'repository' => self::REPOSITORY,
            'target-dir' => self::LOCATION,
            'version' => self::VERSION,
        ]);

        $this->assertInstanceOf(ComposerInstallCommand::class, $command);
        $this->assertMatchesRegularExpression('#git checkout '.self::VERSION.'#', (string) $command);
    }

    /**
     * @dataProvider provideRequiredProperties
     */
    public function test_it_complains_if_a_required_property_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'repository' => self::REPOSITORY,
            'target-dir' => self::LOCATION,
            'version' => self::VERSION,
        ];
        unset($properties[$property]);

        ComposerInstallCommandFactory::import($properties);
    }

    public static function provideRequiredProperties()
    {
        yield ['repository'];
        yield ['target-dir'];
    }
}
