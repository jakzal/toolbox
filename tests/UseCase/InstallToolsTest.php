<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;
use Zalas\Toolbox\Tool\Command\FileDownloadCommand;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;
use Zalas\Toolbox\Tool\Command\PhiveInstallCommand;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;
use Zalas\Toolbox\UseCase\InstallTools;

class InstallToolsTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var InstallTools
     */
    private $useCase;

    /**
     * @var Tools|ObjectProphecy
     */
    private $tools;

    protected function setUp(): void
    {
        $this->tools = $this->prophesize(Tools::class);
        $this->useCase = new InstallTools($this->tools->reveal());
    }

    public function test_it_returns_a_multi_step_command()
    {
        $filter = $this->filter();

        $this->tools->all($filter)->willReturn(Collection::create([$this->tool(new ShCommand('echo "Foo"'))]));

        $command = $this->useCase->__invoke($filter);

        $this->assertInstanceOf(MultiStepCommand::class, $command);
    }

    public function test_it_groups_composer_global_install_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new ComposerGlobalInstallCommand('phpstan/phpstan')),
            $this->tool(new ComposerGlobalInstallCommand('phan/phan')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#composer global require .* phpstan/phpstan phan/phan#', (string)$command);
    }

    public function test_it_does_not_include_empty_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new ShCommand('echo "Foo"')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertDoesNotMatchRegularExpression('#composer global require#', (string)$command, 'Composer commands are not grouped if there is none.');
        $this->assertDoesNotMatchRegularExpression('#&&\s*$#', (string)$command, 'Empty commands are not included.');
    }

    public function test_it_groups_composer_bin_plugin_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new ComposerBinPluginCommand('phpstan/phpstan', 'tools', Collection::create([]))),
            $this->tool(new ComposerBinPluginCommand('phan/phan', 'tools', Collection::create([]))),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#composer global bin tools require .* phpstan/phpstan phan/phan#', (string)$command);
    }

    public function test_it_includes_installation_tagged_commands_before_other_ones()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new ShCommand('echo "Foo"')),
            $this->tool(new ShCommand('echo "Installation"'), [InstallTools::PRE_INSTALLATION_TAG]),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#echo "Installation".*echo "Foo"#smi', (string)$command, 'Installation commands are included before other ones.');
        $this->assertDoesNotMatchRegularExpression('#echo "Installation".*echo "Installation"#smi', (string)$command, 'Installation commands are not duplicated.');
    }

    public function test_it_includes_shell_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new ShCommand('echo "Foo"')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#echo "Foo"#', (string)$command);
    }

    public function test_it_includes_multi_step_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new MultiStepCommand(Collection::create([
                new ShCommand('echo "Foo"'),
                new ShCommand('echo "Bar"')
            ]))),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#echo "Foo" && echo "Bar"#', (string)$command);
    }

    public function test_it_includes_composer_install_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new ComposerInstallCommand('git@github.com:phpspec/phpspec.git', '/usr/local/bin')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#git clone git@github.com:phpspec/phpspec.git#', (string)$command);
    }

    public function test_it_includes_box_build_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new BoxBuildCommand('https://github.com/behat/behat.git', 'behat.phar', '/tools/behat', '/tmp')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#box-legacy build#', (string)$command);
    }

    public function test_it_includes_phar_download_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new PharDownloadCommand('https://github.com/sensiolabs-de/deptrac/releases/download/0.2.0/deptrac-0.2.0.phar', '/tools/phar')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#curl[^&]*?deptrac-0.2.0.phar#', (string)$command);
    }

    public function test_it_includes_phive_install_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new PhiveInstallCommand('phpunit', '/tools/phpunit')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#phive install[^&]*?phpunit[^&]*?/tools/phpunit#', (string)$command);
    }

    public function test_it_includes_file_download_commands()
    {
        $this->tools->all(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->tool(new FileDownloadCommand('https://github.com/fabpot/local-php-security-checker/releases/download/v1.0.0/local-php-security-checker_1.0.0_linux_amd64', '/tools/security-checker')),
        ]));

        $command = $this->useCase->__invoke($this->filter());

        $this->assertMatchesRegularExpression('#curl[^&]*?local-php-security-checker_1.0.0_linux_amd64#', (string)$command);
    }

    private function filter(): Filter
    {
        return new Filter([], []);
    }

    private function tool(Command $command, array $tags = []): Tool
    {
        $tool = $this->prophesize(Tool::class);
        $tool->command()->willReturn($command);
        $tool->tags()->willReturn($tags);

        return $tool->reveal();
    }
}
