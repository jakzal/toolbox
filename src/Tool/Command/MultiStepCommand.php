<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use InvalidArgumentException;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;

final class MultiStepCommand implements Command
{
    private Collection $commands;
    private mixed $glue;

    public function __construct(Collection $commands, $glue = ' && ')
    {
        if ($commands->empty()) {
            throw new InvalidArgumentException('Collection of commands cannot be empty.');
        }

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
