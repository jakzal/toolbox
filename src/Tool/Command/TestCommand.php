<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class TestCommand implements Command
{
    private string $command;
    private string $name;

    public function __construct(string $command, string $name)
    {
        $this->command = $command;
        $this->name = $name;
    }

    public function __toString(): string
    {
        return \sprintf('((%s > /dev/null && echo -e "\e[0;32m✔\e[0m︎%s") || (echo -e "\e[1;31m✘\e[0m%s" && false))', $this->command, $this->name, $this->name);
    }
}
