<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Command;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Toolbox\Cli\Command\InstallCommand;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tests\Prophecy\Prophecy;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\UseCase\InstallTools;

class InstallCommandTest extends ToolboxCommandTestCase
{
    use Prophecy;

    protected const CLI_COMMAND_NAME = InstallCommand::NAME;

    /**
     * @var Runner|ObjectProphecy
     */
    private $runner;

    /**
     * @var InstallTools|ObjectProphecy
     */
    private $useCase;

    protected function setUp(): void
    {
        $this->runner = $this->prophesize(Runner::class);
        $this->useCase = $this->prophesize(InstallTools::class);

        parent::setUp();
    }

    public function test_it_runs_the_install_tools_use_case()
    {
        $command = $this->createCommand();
        $this->useCase->__invoke(Argument::type(Filter::class))->willReturn($command);
        $this->runner->run($command)->willReturn(0);

        $tester = $this->executeCliCommand();

        $this->runner->run($command)->shouldHaveBeenCalled();

        $this->assertSame(0, $tester->getStatusCode());
    }

    public function test_it_returns_the_status_code_of_the_run()
    {
        $this->useCase->__invoke(Argument::type(Filter::class))->willReturn($this->createCommand());
        $this->runner->run(Argument::any())->willReturn(1);

        $tester = $this->executeCliCommand();

        $this->assertSame(1, $tester->getStatusCode());
    }

    public function test_it_filters_by_tags()
    {
        $this->useCase->__invoke(Argument::type(Filter::class))->willReturn($this->createCommand());
        $this->runner->run(Argument::any())->willReturn(0);

        $this->executeCliCommand(['--exclude-tag' => ['foo'], '--tag' => ['bar']]);

        $this->useCase->__invoke(new Filter(['foo'], ['bar']))->shouldBeCalled();
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
            Runner::class => $this->runner->reveal(),
            InstallTools::class => $this->useCase->reveal(),
        ];
    }

    private function createCommand(): Command
    {
        $command = $this->prophesize(Command::class);
        $command->__toString()->willReturn('echo "foo"');

        return $command->reveal();
    }
}
