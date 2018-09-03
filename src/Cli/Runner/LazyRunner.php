<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Runner;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Zalas\Toolbox\Runner\PassthruRunner;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

final class LazyRunner implements Runner
{
    private $container;

    private $runner;

    public function __construct(ContainerInterface $container)
    {
        $this->guardServiceAvailability($container);

        $this->container = $container;
    }

    public function run(Command $command): int
    {
        return $this->runner()->run($command);
    }

    private function runner(): Runner
    {
        if (null === $this->runner) {
            $this->runner = $this->initializeRunner();
        }

        return $this->runner;
    }

    private function initializeRunner(): Runner
    {
        if ($this->container->get(InputInterface::class)->getOption('dry-run')) {
            return $this->container->get(DryRunner::class);
        }

        return $this->container->get(PassthruRunner::class);
    }

    private function guardServiceAvailability(ContainerInterface $container): void
    {
        $requiredServices = [
            InputInterface::class,
            PassthruRunner::class,
            DryRunner::class,
        ];

        foreach ($requiredServices as $serviceId) {
            if (!$container->has($serviceId)) {
                throw new class(\sprintf('The service "%s" is missing.', $serviceId)) extends \LogicException implements ContainerExceptionInterface {
                };
            }
        }
    }
}
