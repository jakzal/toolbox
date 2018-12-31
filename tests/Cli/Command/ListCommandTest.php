<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Command;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Zalas\Toolbox\Cli\Command\ListCommand;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\UseCase\ListTools;

class ListCommandTest extends ToolboxCommandTestCase
{
    protected const CLI_COMMAND_NAME = ListCommand::NAME;

    /**
     * @var ListTools|ObjectProphecy
     */
    private $useCase;

    protected function setUp()
    {
        $this->useCase = $this->prophesize(ListTools::class);

        parent::setUp();
    }

    public function test_it_runs_the_list_tools_use_case()
    {
        $this->useCase->__invoke(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->createTool('Behat', 'Tests business expectations', 'http://behat.org'),
        ]));

        $tester = $this->executeCliCommand();

        $this->assertSame(0, $tester->getStatusCode());
        $this->assertRegExp('#Available tools#i', $tester->getDisplay());
        $this->assertRegExp('#Behat.*?Tests business expectations.*?http://behat.org#smi', $tester->getDisplay());
    }

    public function test_it_filters_by_tags()
    {
        $this->useCase->__invoke(Argument::type(Filter::class))->willReturn(Collection::create([
            $this->createTool('Behat', 'Tests business expectations', 'http://behat.org'),
        ]));

        $this->executeCliCommand(['--exclude-tag' => ['foo'], '--tag' => ['bar']]);

        $this->useCase->__invoke(new Filter(['foo'], ['bar']))->shouldHaveBeenCalled();
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
            ListTools::class => $this->useCase->reveal(),
        ];
    }

    private function createTool(string $name, string $summary, string $website): Tool
    {
        $tool = $this->prophesize(Tool::class);
        $tool->name()->willReturn($name);
        $tool->summary()->willReturn($summary);
        $tool->website()->willReturn($website);

        return $tool->reveal();
    }
}
