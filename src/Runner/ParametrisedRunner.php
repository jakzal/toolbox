<?php declare(strict_types=1);

namespace Zalas\Toolbox\Runner;

use Zalas\Toolbox\Tool\Command;

final class ParametrisedRunner implements Runner
{
    private Runner $decoratedRunner;
    private array $parameters;

    public function __construct(Runner $decoratedRunner, array $parameters)
    {
        $this->decoratedRunner = $decoratedRunner;
        $this->parameters = $parameters;
    }

    public function run(Command $command): int
    {
        return $this->decoratedRunner->run(new class($command, $this->parameters) implements Command {
            private Command $command;
            private array $parameters;

            public function __construct(Command $command, array $parameters)
            {
                $this->command = $command;
                $this->parameters = $parameters;
            }

            public function __toString(): string
            {
                return \strtr((string) $this->command, $this->parameters);
            }
        });
    }
}
