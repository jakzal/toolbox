<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Command;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Toolbox\Cli\Command\TestCommand;
use Zalas\Toolbox\Cli\Runner;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\UseCase\TestTools;

class TestCommandTest extends ToolboxCommandTestCase
{
    protected const CLI_COMMAND_NAME = TestCommand::NAME;

    /**
     * @var Runner|ObjectProphecy
     */
    private $runner;

    /**
     * @var TestTools|ObjectProphecy
     */
    private $useCase;

    protected function setUp()
    {
        $this->runner = $this->prophesize(Runner::class);
        $this->useCase = $this->prophesize(TestTools::class);

        parent::setUp();
    }

    public function test_it_runs_the_test_tools_use_case()
    {
        $command = $this->createCommand();
        $this->useCase->__invoke()->willReturn($command);
        $this->runner->run($command)->willReturn(0);

        $tester = $this->executeCliCommand();

        $this->runner->run($command)->shouldHaveBeenCalled();

        $this->assertSame(0, $tester->getStatusCode());
    }

    public function test_it_returns_the_status_code_of_the_run()
    {
        $this->useCase->__invoke()->willReturn($this->createCommand());
        $this->runner->run(Argument::any())->willReturn(1);

        $tester = $this->executeCliCommand();

        $this->assertSame(1, $tester->getStatusCode());
    }

    protected function getContainerTestDoubles(): array
    {
        return [
            Runner::class => $this->runner->reveal(),
            TestTools::class => $this->useCase->reveal(),
        ];
    }

    private function createCommand(): Command
    {
        $command = $this->prophesize(Command::class);
        $command->__toString()->willReturn('true');

        return $command->reveal();
    }
}
