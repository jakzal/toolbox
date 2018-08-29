<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\ComposerGlobalInstallCommandFactory;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;

class ComposerGlobalInstallCommandFactoryTest extends TestCase
{
    private const PACKAGE = 'phan/phan';

    public function test_creates_a_command()
    {
        $command = ComposerGlobalInstallCommandFactory::import([
            'package' => self::PACKAGE,
        ]);

        $this->assertInstanceOf(ComposerGlobalInstallCommand::class, $command);
    }

    public function test_it_complains_if_package_is_missing()
    {
        $this->expectException(\InvalidArgumentException::class);

        ComposerGlobalInstallCommandFactory::import([]);
    }
}
