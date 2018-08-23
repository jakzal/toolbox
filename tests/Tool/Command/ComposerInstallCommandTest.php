<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;

class ComposerInstallCommandTest extends TestCase
{
    const REPOSITORY = 'https://github.com/behat/behat.git';
    const VERSION = 'v3.4.0';

    /**
     * @var ComposerInstallCommand
     */
    private $command;

    protected function setUp()
    {
        $this->command = ComposerInstallCommand::import([
            'repository' => self::REPOSITORY,
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
        $this->assertRegExp('#git checkout '.self::VERSION.'#', (string) $this->command);
        $this->assertRegExp('#composer install --no-dev --no-suggest --prefer-dist -n#', (string) $this->command);
    }

    public function test_it_tries_to_guess_version_number_if_not_given_one()
    {
        $command = ComposerInstallCommand::import([
            'repository' => self::REPOSITORY,
        ]);

        $this->assertRegExp('#git checkout \$\(git describe --tags .*?\)#', (string) $command);
    }

    public function test_it_uses_a_generic_directory_if_name_cannot_be_guessed_from_the_repository()
    {
        $command = ComposerInstallCommand::import([
            'repository' => 'example.com:foo.git',
        ]);

        $this->assertRegExp('#cd /tools/tmp#', (string) $command);
    }

    public function test_it_complains_if_repository_property_is_missing()
    {
        $this->expectException(\InvalidArgumentException::class);

        ComposerInstallCommand::import([]);
    }
}
