<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\ServiceContainer;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

final class LazyRunner implements Runner
{
    private ?Runner $runner = null;

    private RunnerFactory $factory;

    public function __construct(RunnerFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(Command $command): int
    {
        return $this->runner()->run($command);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function runner(): Runner
    {
        if (null === $this->runner) {
            $this->runner = $this->factory->createRunner();
        }

        return $this->runner;
    }
}
