<?php declare(strict_types=1);

namespace Zalas\Toolbox\UseCase;

use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;

class TestTools
{
    private $tools;

    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function __invoke(Filter $filter): Command
    {
        return new MultiStepCommand(
            $this->tools->all($filter)->map(function (Tool $tool) {
                return $tool->testCommand();
            })
        );
    }
}
