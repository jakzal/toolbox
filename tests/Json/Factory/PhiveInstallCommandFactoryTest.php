<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\PhiveInstallCommandFactory;
use Zalas\Toolbox\Tool\Command\PhiveInstallCommand;

class PhiveInstallCommandFactoryTest extends TestCase
{
    private const ALIAS = 'example/foo';
    private const BIN = '/usr/local/bin/foo';
    private const SIG = '0000000000000000';

    public function test_it_creates_a_command()
    {
        $command = PhiveInstallCommandFactory::import([
            'alias' => self::ALIAS,
            'bin' => self::BIN,
            'sig' => self::SIG
        ]);
        
        $this->assertInstanceOf(PhiveInstallCommand::class, $command);
        $this->assertStringNotContainsString('unsigned', (string)$command);
    }

    #[DataProvider('provideRequiredProperties')]
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'alias' => self::ALIAS,
            'bin' => self::BIN,
        ];

        unset($properties[$property]);

        $command = PhiveInstallCommandFactory::import($properties);
        $this->assertStringContainsString('unsigned', (string)$command);
    }

    public function test_it_accepts_unsigned_phars()
    {
        $properties = [
            'alias' => self::ALIAS,
            'bin' => self::BIN
        ];

        $command = PhiveInstallCommandFactory::import($properties);
        $this->assertStringContainsString('unsigned', (string)$command);
    }

    public static function provideRequiredProperties(): \Generator
    {
        yield ['alias'];
        yield ['bin'];
    }
}
