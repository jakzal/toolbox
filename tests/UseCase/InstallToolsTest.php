<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;
use Zalas\Toolbox\UseCase\InstallTools;

class InstallToolsTest extends TestCase
{
    /**
     * @var InstallTools
     */
    private $useCase;

    /**
     * @var Tools|ObjectProphecy
     */
    private $tools;

    protected function setUp()
    {
        $this->tools = $this->prophesize(Tools::class);
        $this->useCase = new InstallTools($this->tools->reveal());
    }

    public function test_it_returns_a_multi_step_command()
    {
        $this->tools->all()->willReturn(Collection::create([]));

        $command = $this->useCase->__invoke();

        $this->assertInstanceOf(MultiStepCommand::class, $command);
    }

    public function test_it_groups_composer_global_install_commands()
    {
        $this->tools->all()->willReturn(Collection::create([
            $this->tool(ComposerGlobalInstallCommand::import(['package' => 'phpstan/phpstan'])),
            $this->tool(ComposerGlobalInstallCommand::import(['package' => 'phan/phan'])),
        ]));

        $command = $this->useCase->__invoke();

        $this->assertRegExp('#composer global require .* phpstan/phpstan phan/phan#', (string)$command);
    }

    public function test_it_groups_composer_bin_plugin_commands()
    {
        $this->tools->all()->willReturn(Collection::create([
            $this->tool(ComposerBinPluginCommand::import(['package' => 'phpstan/phpstan', 'namespace' => 'tools'])),
            $this->tool(ComposerBinPluginCommand::import(['package' => 'phan/phan', 'namespace' => 'tools'])),
        ]));

        $command = $this->useCase->__invoke();

        $this->assertRegExp('#composer global bin tools require .* phpstan/phpstan phan/phan#', (string)$command);
    }

    public function test_it_includes_shell_commands()
    {
        $this->tools->all()->willReturn(Collection::create([
            $this->tool(new ShCommand('echo "Foo"')),
        ]));

        $command = $this->useCase->__invoke();

        $this->assertRegExp('#echo "Foo"#', (string)$command);
    }

    public function test_it_includes_multi_step_commands()
    {
        $this->tools->all()->willReturn(Collection::create([
            $this->tool(new MultiStepCommand(Collection::create([
                new ShCommand('echo "Foo"'),
                new ShCommand('echo "Bar"')
            ]))),
        ]));

        $command = $this->useCase->__invoke();

        $this->assertRegExp('#echo "Foo" && echo "Bar"#', (string)$command);
    }

    public function test_it_includes_composer_install_commands()
    {
        $this->tools->all()->willReturn(Collection::create([
            $this->tool(ComposerInstallCommand::import(['repository' => 'git@github.com:phpspec/phpspec.git'])),
        ]));

        $command = $this->useCase->__invoke();

        $this->assertRegExp('#git clone git@github.com:phpspec/phpspec.git#', (string)$command);
    }

    public function test_it_includes_box_build_commands()
    {
        $this->tools->all()->willReturn(Collection::create([
            $this->tool(BoxBuildCommand::import(['repository' => 'https://github.com/behat/behat.git', 'phar' => 'behat.phar', 'bin' => '/tools/behat'])),
        ]));

        $command = $this->useCase->__invoke();

        $this->assertRegExp('#box build#', (string)$command);
    }

    public function test_it_includes_phar_download_commands()
    {
        $this->tools->all()->willReturn(Collection::create([
            $this->tool(PharDownloadCommand::import(['phar' => 'https://github.com/sensiolabs-de/deptrac/releases/download/0.2.0/deptrac-0.2.0.phar', 'bin' => '/tools/phar'])),
        ]));

        $command = $this->useCase->__invoke();

        $this->assertRegExp('#curl[^&]*?deptrac-0.2.0.phar#', (string)$command);
    }

    private function tool(Command $command): Tool
    {
        $tool = $this->prophesize(Tool::class);
        $tool->command()->willReturn($command);

        return $tool->reveal();
    }
}
