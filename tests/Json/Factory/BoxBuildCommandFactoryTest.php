<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\BoxBuildCommandFactory;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;

class BoxBuildCommandFactoryTest extends TestCase
{
    private const REPOSITORY = 'https://github.com/behat/behat.git';

    private const PHAR = 'behat.phar';

    private const BIN = '/usr/local/bin/behat';

    private const WORK_DIR = '/tools';

    private const VERSION = 'v3.4.0';

    public function test_it_creates_a_command()
    {
        $command = BoxBuildCommandFactory::import([
            'repository' => self::REPOSITORY,
            'phar' => self::PHAR,
            'bin' => self::BIN,
            'work-dir' => self::WORK_DIR,
            'version' => self::VERSION,
        ]);

        $this->assertInstanceOf(BoxBuildCommand::class, $command);
        $this->assertRegExp('#git checkout '.self::VERSION.'#', (string) $command);
    }

    public function test_the_version_is_not_required()
    {
        $command = BoxBuildCommandFactory::import([
            'repository' => self::REPOSITORY,
            'phar' => self::PHAR,
            'bin' => self::BIN,
            'work-dir' => self::WORK_DIR,
        ]);

        $this->assertInstanceOf(BoxBuildCommand::class, $command);
    }

    /**
     * @dataProvider provideRequiredProperties
     */
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'repository' => self::REPOSITORY,
            'phar' => self::PHAR,
            'bin' => self::BIN,
            'work-dir' => self::WORK_DIR,
        ];

        unset($properties[$property]);

        BoxBuildCommandFactory::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['repository'];
        yield ['phar'];
        yield ['bin'];
        yield ['work-dir'];
    }
}
