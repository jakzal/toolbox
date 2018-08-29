<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;

final class MultiStepCommand implements Command
{
    private $commands;
    private $glue;

    public function __construct(Collection $commands, $glue = ' && ')
    {
        $this->commands = $commands->filter(function (Command $c) {
            return $c;
        });
        $this->glue = $glue;
    }

    public function __toString(): string
    {
        return \implode($this->glue, $this->commands->toArray());
    }
}
