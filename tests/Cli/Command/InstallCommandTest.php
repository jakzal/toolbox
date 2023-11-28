<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Command;

use PHPUnit\Framework\MockObject\MockObject;
use Zalas\Toolbox\Cli\Command\InstallCommand;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\UseCase\InstallTools;

class InstallCommandTest extends ToolboxCommandTestCase
{
    protected const CLI_COMMAND_NAME = InstallCommand::NAME;

    /**
     * @var Runner|MockObject
     */
    private $runner;

    /**
     * @var InstallTools|MockObject
     */
    private $useCase;

    protected function setUp(): void
    {
        $this->runner = $this->createMock(Runner::class);
        $this->useCase = $this->createMock(InstallTools::class);

        parent::setUp();
    }

    public function test_it_runs_the_install_tools_use_case()
    {
        $command = $this->createCommand();
        $this->useCase->method('__invoke')->willReturn($command);
        $this->runner->expects(self::once())->method('run')->with($command)->willReturn(0);

        $tester = $this->executeCliCommand();

        $this->assertSame(0, $tester->getStatusCode());
    }

    public function test_it_returns_the_status_code_of_the_run()
    {
        $this->useCase->method('__invoke')->willReturn($this->createCommand());
        $this->runner->method('run')->willReturn(1);

        $tester = $this->executeCliCommand();

        $this->assertSame(1, $tester->getStatusCode());
    }

    public function test_it_filters_by_tags()
    {
        $this->useCase->expects(self::once())
            ->method('__invoke')
            ->with(new Filter(['foo'], ['bar']))
            ->willReturn($this->createCommand());
        $this->runner->method('run')->willReturn(0);

        $this->executeCliCommand(['--exclude-tag' => ['foo'], '--tag' => ['bar']]);
    }

    public function test_it_defines_dry_run_option()
    {
        $this->assertTrue($this->cliCommand()->getDefinition()->hasOption('dry-run'));
    }

    public function test_it_defines_target_dir_option()
    {
        $this->assertTrue($this->cliCommand()->getDefinition()->hasOption('target-dir'));
        $this->assertSame('/usr/local/bin', $this->cliCommand()->getDefinition()->getOption('target-dir')->getDefault());
    }

    /**
     * @putenv TOOLBOX_TARGET_DIR=/tmp
     */
    public function test_it_takes_the_target_dir_option_default_from_environment_if_present()
    {
        $this->assertSame('/tmp', $this->cliCommand()->getDefinition()->getOption('target-dir')->getDefault());
    }

    public function test_it_defines_exclude_tag_option()
    {
        $this->assertTrue($this->cliCommand()->getDefinition()->hasOption('exclude-tag'));
        $this->assertSame([], $this->cliCommand()->getDefinition()->getOption('exclude-tag')->getDefault());
    }

    /**
     * @putenv TOOLBOX_EXCLUDED_TAGS=foo,bar,baz
     */
    public function test_it_takes_the_excluded_tag_option_default_from_environment_if_present()
    {
        $this->assertSame(['foo', 'bar', 'baz'], $this->cliCommand()->getDefinition()->getOption('exclude-tag')->getDefault());
    }

    public function test_it_defines_tag_option()
    {
        $this->assertTrue($this->cliCommand()->getDefinition()->hasOption('tag'));
        $this->assertSame([], $this->cliCommand()->getDefinition()->getOption('tag')->getDefault());
    }

    /**
     * @putenv TOOLBOX_TAGS=foo,bar,baz
     */
    public function test_it_takes_the_tag_option_default_from_environment_if_present()
    {
        $this->assertSame(['foo', 'bar', 'baz'], $this->cliCommand()->getDefinition()->getOption('tag')->getDefault());
    }

    protected function getContainerTestDoubles(): array
    {
        return [
            Runner::class => $this->runner,
            InstallTools::class => $this->useCase,
        ];
    }

    private function createCommand(): Command
    {
        return new ShCommand('echo "foo"');
    }
}
