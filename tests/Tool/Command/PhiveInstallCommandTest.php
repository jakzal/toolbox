<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\PhiveInstallCommand;

class PhiveInstallCommandTest extends TestCase
{
    private const ALIAS = 'example/foo';
    private const BIN = '/usr/local/bin/foo';
    private const SIG = '0000000000000000';

    private PhiveInstallCommand $command;

    protected function setUp(): void
    {
        $this->command = new PhiveInstallCommand(self::ALIAS, self::BIN, self::SIG);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertMatchesRegularExpression(\sprintf('#phive --no-progress --home [^\s]*? install --trust-gpg-keys %s %s -t [^\s]++ && mv [^\s]+? %s#', self::SIG, self::ALIAS, self::BIN), (string) $this->command);
    }

    public function test_it_accepts_unsigned_phar_command()
    {
        $command = new PhiveInstallCommand(self::ALIAS, self::BIN);
        $this->assertMatchesRegularExpression(\sprintf('#phive --no-progress --home [^\s]*? install --force-accept-unsigned %s -t [^\s]++ && mv [^\s]+?#', self::ALIAS, self::BIN), (string) $command);
    }
}
