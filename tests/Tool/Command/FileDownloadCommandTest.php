<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\FileDownloadCommand;

class FileDownloadCommandTest extends TestCase
{
    const URL = 'https://example.com/file';
    const FILE = '/usr/local/bin/file.txt';

    /**
     * @var FileDownloadCommand
     */
    private $command;

    protected function setUp()
    {
        $this->command = FileDownloadCommand::import([
            'url' => self::URL,
            'file' => self::FILE,
        ]);
    }

    public function test_it_is_a_command()
    {
        $this->assertInstanceOf(Command::class, $this->command);
    }

    public function test_it_generates_the_installation_command()
    {
        $this->assertRegExp(\sprintf('#curl .*? %s -o %s#', self::URL, self::FILE), (string) $this->command);
    }

    /**
     * @dataProvider provideRequiredProperties
     */
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'url' => self::URL,
            'file' => self::FILE,
        ];

        unset($properties[$property]);

        FileDownloadCommand::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['url'];
        yield ['file'];
    }
}
