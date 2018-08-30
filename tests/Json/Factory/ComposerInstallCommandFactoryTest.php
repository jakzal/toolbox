<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\ComposerInstallCommandFactory;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;

class ComposerInstallCommandFactoryTest extends TestCase
{
    private const REPOSITORY = 'https://github.com/behat/behat.git';
    private const VERSION = 'v3.4.0';

    public function test_it_creates_a_command()
    {
        $command = ComposerInstallCommandFactory::import([
            'repository' => self::REPOSITORY,
            'version' => self::VERSION,
        ]);

        $this->assertInstanceOf(ComposerInstallCommand::class, $command);
        $this->assertRegExp('#git checkout '.self::VERSION.'#', (string) $command);
    }

    public function test_it_complains_if_repository_property_is_missing()
    {
        $this->expectException(\InvalidArgumentException::class);

        ComposerInstallCommandFactory::import([]);
    }
}
