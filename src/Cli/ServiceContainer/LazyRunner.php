<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\ServiceContainer;

use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

final class LazyRunner implements Runner
{
    private $runner;

    private $factory;

    public function __construct(RunnerFactory $factory)
    {
        $this->factory = $factory;
    }

    public function run(Command $command): int
    {
        return $this->runner()->run($command);
    }

    private function runner(): Runner
    {
        if (null === $this->runner) {
            $this->runner = $this->factory->createRunner();
        }

        return $this->runner;
    }
}
