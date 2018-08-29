<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class ShCommand implements Command
{
    private $command;

    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function __toString(): string
    {
        return $this->command;
    }
}
