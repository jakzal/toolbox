<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\ServiceContainer;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Runner\ParametrisedRunner;
use Zalas\Toolbox\Runner\PassthruRunner;
use Zalas\Toolbox\Runner\Runner;

class RunnerFactory
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createRunner(): Runner
    {
        $runner = $this->createRealRunner();

        if ($parameters = $this->parameters()) {
            return new ParametrisedRunner($runner, $parameters);
        }

        return $runner;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createRealRunner(): DryRunner|PassthruRunner
    {
        if ($this->container->get(InputInterface::class)->getOption('dry-run')) {
            return new DryRunner($this->container->get(OutputInterface::class));
        }

        return new PassthruRunner();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function parameters(): array
    {
        if ($targetDir = $this->targetDir()) {
            return ['%target-dir%' => $targetDir];
        }

        return [];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function targetDir(): ?string
    {
        if (!$this->container->get(InputInterface::class)->hasOption('target-dir')) {
            return null;
        }

        $targetDir = $this->container->get(InputInterface::class)->getOption('target-dir');

        if (!\is_dir($targetDir)) {
            throw new class(\sprintf('The target dir does not exist: "%s".', $targetDir)) extends \RuntimeException implements ContainerExceptionInterface {
            };
        }

        return \realpath($targetDir);
    }
}
