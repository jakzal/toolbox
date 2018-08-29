<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\PharDownloadCommandFactory;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;

class PharDownloadCommandFactoryTest extends TestCase
{
    private const PHAR = 'https://example.com/foo.phar';
    private const BIN = '/usr/local/bin/foo';

    public function test_it_creates_a_command()
    {
        $command = PharDownloadCommandFactory::import([
            'phar' => self::PHAR,
            'bin' => self::BIN,
        ]);
        
        $this->assertInstanceOf(PharDownloadCommand::class, $command);
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

        PharDownloadCommandFactory::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['phar'];
        yield ['bin'];
    }
}
