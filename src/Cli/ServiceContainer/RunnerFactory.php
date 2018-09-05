<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\ServiceContainer;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Runner\DryRunner;
use Zalas\Toolbox\Runner\PassthruRunner;
use Zalas\Toolbox\Runner\Runner;

class RunnerFactory
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createRunner(): Runner
    {
        if ($this->container->get(InputInterface::class)->getOption('dry-run')) {
            return new DryRunner($this->container->get(OutputInterface::class));
        }

        return new PassthruRunner();
    }
}
