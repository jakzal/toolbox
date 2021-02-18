<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\PhiveInstallCommandFactory;
use Zalas\Toolbox\Tool\Command\PhiveInstallCommand;

class PhiveInstallCommandFactoryTest extends TestCase
{
    private const ALIAS = 'example/foo';
    private const BIN = '/usr/local/bin/foo';

    public function test_it_creates_a_command()
    {
        $command = PhiveInstallCommandFactory::import([
            'alias' => self::ALIAS,
            'bin' => self::BIN,
        ]);
        
        $this->assertInstanceOf(PhiveInstallCommand::class, $command);
    }

    /**
     * @dataProvider provideRequiredProperties
     */
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = [
            'alias' => self::ALIAS,
            'bin' => self::BIN,
        ];

        unset($properties[$property]);

        PhiveInstallCommandFactory::import($properties);
    }

    public function test_it_accepts_signed_trusted_phars()
    {
        $properties = [
            'alias' => self::ALIAS,
            'bin' => self::BIN,
        ];

        $command = PhiveInstallCommandFactory::import($properties);
        $this->assertStringNotContainsString('trust', (string)$command);
        $this->assertStringNotContainsString('unsigned', (string)$command);
    }

    public function test_it_accepts_signed_untrusted_phars()
    {
        $properties = [
            'alias' => self::ALIAS,
            'bin' => self::BIN,
            'trust' => true,
        ];

        $command = PhiveInstallCommandFactory::import($properties);
        $this->assertStringContainsString('trust', (string)$command);
    }

    public function test_it_accepts_unsigned_phars()
    {
        $properties = [
            'alias' => self::ALIAS,
            'bin' => self::BIN,
            'unsigned' => true,
        ];

        $command = PhiveInstallCommandFactory::import($properties);
        $this->assertStringContainsString('unsigned', (string)$command);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['alias'];
        yield ['bin'];
    }
}
