<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;
use Zalas\Toolbox\Cli\Command\InstallCommand;
use Zalas\Toolbox\Cli\Command\ListCommand;
use Zalas\Toolbox\Cli\Command\TestCommand;
use Zalas\Toolbox\Tool\Tools;
use Zalas\Toolbox\UseCase\InstallTools;
use Zalas\Toolbox\UseCase\ListTools;
use Zalas\Toolbox\UseCase\TestTools;

class ServiceContainer implements ContainerInterface
{
    private $parameters;

    private $services = [
        InstallCommand::class => 'createInstallCommand',
        ListCommand::class => 'createListCommand',
        TestCommand::class => 'createTestCommand',
        Runner::class => 'createRunner',
        InstallTools::class => 'createInstallToolsUseCase',
        ListTools::class => 'createListToolsUseCase',
        TestTools::class => 'createTestToolsUseCase',
        Tools::class => 'createTools',
    ];

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new class(\sprintf('The "%s" service is not registered in the service container.', $id)) extends \RuntimeException implements NotFoundExceptionInterface {
            };
        }

        return \call_user_func([$this, $this->services[$id]]);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return \in_array($id, \array_keys($this->services));
    }

    public function setParameter(string $name, $value): void
    {
        $this->parameters[$name] = $value;
    }

    private function getParameter(string $name)
    {
        if (!isset($this->parameters[$name])) {
            throw new class(\sprintf('The "%s" parameter is not defined.', $name)) extends RuntimeException implements ContainerExceptionInterface {
            };
        }

        return $this->parameters[$name];
    }

    private function createInstallCommand(): InstallCommand
    {
        return new InstallCommand($this->get(InstallTools::class), $this->get(Runner::class));
    }

    private function createListCommand(): ListCommand
    {
        return new ListCommand($this->get(ListTools::class));
    }

    private function createTestCommand(): TestCommand
    {
        return new TestCommand($this->get(TestTools::class), $this->get(Runner::class));
    }

    private function createRunner(): Runner
    {
        return new Runner();
    }

    private function createInstallToolsUseCase(): InstallTools
    {
        return new InstallTools($this->get(Tools::class));
    }

    private function createListToolsUseCase(): ListTools
    {
        return new ListTools($this->get(Tools::class));
    }

    private function createTestToolsUseCase(): TestTools
    {
        return new TestTools($this->get(Tools::class));
    }

    private function createTools(): Tools
    {
        return new Tools($this->getParameter('toolbox_json'));
    }
}
