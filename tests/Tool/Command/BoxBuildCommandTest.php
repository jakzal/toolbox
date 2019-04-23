<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;

class BoxBuildCommandTest extends TestCase
{
    private const REPOSITORY = 'https://github.com/behat/behat.git';

    private const PHAR = 'behat.phar';

    private const BIN = '/usr/local/bin/behat';

    private const TMP_DIR = '/tools';

    private const VERSION = 'v3.4.0';

    public function test_it_is_a_command()
    {
        $command = new BoxBuildCommand(
            self::REPOSITORY,
            self::PHAR,
            self::BIN,
            self::TMP_DIR,
            self::VERSION
        );
        
        $this->assertInstanceOf(Command::class, $command);
    }

    public function test_it_generates_the_installation_command()
    {
        $command = new BoxBuildCommand(
            self::REPOSITORY,
            self::PHAR,
            self::BIN,
            self::TMP_DIR,
            self::VERSION
        );
        
        $this->assertRegExp('#git clone '.self::REPOSITORY.'#', (string) $command);
        $this->assertRegExp('#cd /tools/behat#', (string) $command);
        $this->assertRegExp('#git checkout '.self::VERSION.'#', (string) $command);
        $this->assertRegExp('#composer install --no-dev --no-suggest --prefer-dist -n#', (string) $command);
        $this->assertRegExp('#box-legacy build#', (string) $command);
    }

    public function test_it_tries_to_guess_version_number_if_not_given_one()
    {
        $command = new BoxBuildCommand(
            self::REPOSITORY,
            self::PHAR,
            self::BIN,
            self::TMP_DIR
        );

        $this->assertRegExp('#git checkout \$\(git describe --tags .*?\)#', (string) $command);
    }

    public function test_it_uses_a_generic_directory_if_name_cannot_be_guessed_from_the_repository()
    {
        $command = new BoxBuildCommand(
            'example.com:foo.git',
            self::PHAR,
            self::BIN,
            self::TMP_DIR
        );

        $this->assertRegExp('#cd /tools/tmp#', (string) $command);
    }
}
