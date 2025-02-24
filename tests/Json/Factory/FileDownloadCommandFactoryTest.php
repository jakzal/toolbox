<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\FileDownloadCommandFactory;
use Zalas\Toolbox\Tool\Command\FileDownloadCommand;

class FileDownloadCommandFactoryTest extends TestCase
{
    private const URL = 'https://example.com/file';
    private const FILE = '/usr/local/bin/file.txt';
    
    public function test_it_creates_a_command()
    {
        $command = FileDownloadCommandFactory::import([
            'url' => self::URL,
            'file' => self::FILE,
        ]);

        $this->assertInstanceOf(FileDownloadCommand::class, $command);
    }

    #[DataProvider('provideRequiredProperties')]
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'url' => self::URL,
            'file' => self::FILE,
        ];

        unset($properties[$property]);

        FileDownloadCommandFactory::import($properties);
    }

    public static function provideRequiredProperties(): \Generator
    {
        yield ['url'];
        yield ['file'];
    }
}
