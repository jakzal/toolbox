<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;

class PharDownloadCommandTest extends TestCase
{
    private const PHAR = 'https://example.com/foo.phar';
    private const BIN = '/usr/local/bin/foo';

    private PharDownloadCommand $command;

    protected function setUp(): void
    {
        $this->command = new PharDownloadCommand(self::PHAR, self::BIN);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertMatchesRegularExpression(\sprintf('#curl .*? %s -o %s#', self::PHAR, self::BIN), (string) $this->command);
    }
}
