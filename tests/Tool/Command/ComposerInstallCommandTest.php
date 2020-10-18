<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;

class ComposerInstallCommandTest extends TestCase
{
    private const REPOSITORY = 'https://github.com/behat/behat.git';
    private const VERSION = 'v3.4.0';
    private const TARGET_DIR = '/tools';

    public function test_it_is_a_command()
    {
        $command = new ComposerInstallCommand(self::REPOSITORY, self::TARGET_DIR, self::VERSION);

        $this->assertInstanceOf(Command::class, $command);
    }

    public function test_it_generates_the_installation_command()
    {
        $command = new ComposerInstallCommand(self::REPOSITORY, self::TARGET_DIR, self::VERSION);

        $this->assertMatchesRegularExpression('#git clone '.self::REPOSITORY.'#', (string) $command);
        $this->assertMatchesRegularExpression('#git checkout '.self::VERSION.'#', (string) $command);
        $this->assertMatchesRegularExpression('#composer install --no-dev --no-suggest --prefer-dist -n#', (string) $command);
    }

    public function test_it_tries_to_guess_version_number_if_not_given_one()
    {
        $command = new ComposerInstallCommand(self::REPOSITORY, self::TARGET_DIR);

        $this->assertMatchesRegularExpression('#git checkout \$\(git describe --tags .*?\)#', (string) $command);
    }
}
