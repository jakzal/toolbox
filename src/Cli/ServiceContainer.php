<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Cli\Command\InstallCommand;
use Zalas\Toolbox\Cli\Command\ListCommand;
use Zalas\Toolbox\Cli\Command\TestCommand;
use Zalas\Toolbox\Cli\ServiceContainer\LazyRunner;
use Zalas\Toolbox\Cli\ServiceContainer\RunnerFactory;
use Zalas\Toolbox\Json\JsonTools;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Tools;
use Zalas\Toolbox\UseCase\InstallTools;
use Zalas\Toolbox\UseCase\ListTools;
use Zalas\Toolbox\UseCase\TestTools;

class ServiceContainer implements ContainerInterface
{
    private array $services = [
        InstallCommand::class => 'createInstallCommand',
        ListCommand::class => 'createListCommand',
        TestCommand::class => 'createTestCommand',
        Runner::class => 'createRunner',
        InstallTools::class => 'createInstallToolsUseCase',
        ListTools::class => 'createListToolsUseCase',
        TestTools::class => 'createTestToolsUseCase',
        Tools::class => 'createTools',
    ];

    private array $runtimeServices = [
        InputInterface::class => null,
        OutputInterface::class => null,
    ];

    public function set(string $id, /*object */$service): void
    {
        if (!\array_key_exists($id, $this->runtimeServices)) {
            throw new class(\sprintf('The "%s" runtime service is not expected.', $id)) extends RuntimeException implements ContainerExceptionInterface {
            };
        }

        $this->runtimeServices[$id] = $service;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $id)
    {
        if (isset($this->runtimeServices[$id])) {
            return $this->runtimeServices[$id];
        }

        if (isset($this->services[$id])) {
            return \call_user_func([$this, $this->services[$id]]);
        }

        throw new class(\sprintf('The "%s" service is not registered in the service container.', $id)) extends RuntimeException implements NotFoundExceptionInterface {
        };
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): bool
    {
        return isset($this->services[$id]) || isset($this->runtimeServices[$id]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createInstallCommand(): InstallCommand
    {
        return new InstallCommand($this->get(InstallTools::class), $this->get(Runner::class));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createListCommand(): ListCommand
    {
        return new ListCommand($this->get(ListTools::class));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createTestCommand(): TestCommand
    {
        return new TestCommand($this->get(TestTools::class), $this->get(Runner::class));
    }

    private function createRunner(): Runner
    {
        return new LazyRunner(new RunnerFactory($this));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createInstallToolsUseCase(): InstallTools
    {
        return new InstallTools($this->get(Tools::class));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createListToolsUseCase(): ListTools
    {
        return new ListTools($this->get(Tools::class));
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function createTestToolsUseCase(): TestTools
    {
        return new TestTools($this->get(Tools::class));
    }

    private function createTools(): Tools
    {
        return new JsonTools(function (): array {
            return $this->get(InputInterface::class)->getOption('tools');
        });
    }
}
