<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;

class PharDownloadCommandTest extends TestCase
{
    const PHAR = 'https://example.com/foo.phar';
    const BIN = '/usr/local/bin/foo';

    /**
     * @var FileDownloadCommand
     */
    private $command;

    protected function setUp()
    {
        $this->command = PharDownloadCommand::import([
            'phar' => self::PHAR,
            'bin' => self::BIN,
        ]);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertRegExp(\sprintf('#curl .*? %s -o %s#', self::PHAR, self::BIN), (string) $this->command);
    }

    /**
     * @dataProvider provideRequiredProperties
     */
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'phar' => self::PHAR,
            'bin' => self::BIN,
        ];

        unset($properties[$property]);

        PharDownloadCommand::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['phar'];
        yield ['bin'];
    }
}
