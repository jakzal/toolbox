<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;

class BoxBuildCommandTest extends TestCase
{
    const REPOSITORY = 'https://github.com/behat/behat.git';

    const PHAR = 'behat.phar';

    const BIN = '/usr/local/bin/behat';

    const VERSION = 'v3.4.0';

    /**
     * @var BoxBuildCommand
     */
    private $command;

    protected function setUp()
    {
        $this->command = BoxBuildCommand::import([
            'repository' => self::REPOSITORY,
            'phar' => self::PHAR,
            'bin' => self::BIN,
            'version' => self::VERSION,
        ]);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertRegExp('#git clone '.self::REPOSITORY.'#', (string) $this->command);
        $this->assertRegExp('#cd /tools/behat#', (string) $this->command);
        $this->assertRegExp('#git checkout '.self::VERSION.'#', (string) $this->command);
        $this->assertRegExp('#composer install --no-dev --no-suggest --prefer-dist -n#', (string) $this->command);
        $this->assertRegExp('#box build#', (string) $this->command);
    }

    public function test_it_tries_to_guess_version_number_if_not_given_one()
    {
        $command = BoxBuildCommand::import([
            'repository' => self::REPOSITORY,
            'phar' => self::PHAR,
            'bin' => self::BIN,
        ]);

        $this->assertRegExp('#git checkout \$\(git describe --tags .*?\)#', (string) $command);
    }

    public function test_it_uses_a_generic_directory_if_name_cannot_be_guessed_from_the_repository()
    {
        $command = BoxBuildCommand::import([
            'repository' => 'example.com:foo.git',
            'phar' => self::PHAR,
            'bin' => self::BIN,
        ]);

        $this->assertRegExp('#cd /tools/tmp#', (string) $command);
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
        ];

        unset($properties[$property]);

        BoxBuildCommand::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['repository'];
        yield ['phar'];
        yield ['bin'];
    }
}
