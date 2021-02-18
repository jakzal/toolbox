<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\PhiveInstallCommand;

class PhiveInstallCommandTest extends TestCase
{
    private const ALIAS = 'example/foo';
    private const BIN = '/usr/local/bin/foo';

    private $command;

    protected function setUp(): void
    {
        $this->command = new PhiveInstallCommand(self::ALIAS, self::BIN);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertMatchesRegularExpression(\sprintf('#phive install(\s++)--trust-gpg-keys(\s++)%s -t %s#', self::ALIAS, self::BIN), (string) $this->command);
    }

    public function test_it_trusts_gpg_keys_command()
    {
        $command = new PhiveInstallCommand(self::ALIAS, self::BIN, false);
        $this->assertMatchesRegularExpression(\sprintf('#phive install(\s++)%s -t %s#', self::ALIAS, self::BIN), (string) $command);
    }

    public function test_it_accepts_unsigned_phar_command()
    {
        $command = new PhiveInstallCommand(self::ALIAS, self::BIN, false, true);
        $this->assertMatchesRegularExpression(\sprintf('#phive install(\s++)--force-accept-unsigned(\s++)%s -t %s#', self::ALIAS, self::BIN), (string) $command);
    }
}
