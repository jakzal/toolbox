<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Cli\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Zalas\Toolbox\Cli\Application;
use Zalas\Toolbox\Cli\ServiceContainer;

abstract class ToolboxCommandTestCase extends TestCase
{
    protected const CLI_COMMAND_NAME = '';

    protected Application $app;

    protected function setUp(): void
    {
        $this->app = new Application('test', $this->createServiceContainer());
    }

    public function test_it_provides_help()
    {
        $this->assertNotEmpty($this->cliCommand()->getDescription());
    }

    protected function getContainerTestDoubles(): array
    {
        return [];
    }

    protected function executeCliCommand(array $input = []): CommandTester
    {
        $tester = new CommandTester($this->cliCommand());
        $tester->execute($input);

        return $tester;
    }

    protected function cliCommand(): Command
    {
        return $this->app->find(static::CLI_COMMAND_NAME);
    }

    private function createServiceContainer(): ServiceContainer
    {
        return new class($this->getContainerTestDoubles()) extends ServiceContainer {
            private array $services;

            public function __construct(array $services)
            {
                $this->services = $services;
            }

            public function get($id)
            {
                if (isset($this->services[$id])) {
                    return $this->services[$id];
                }

                return parent::get($id);
            }
        };
    }
}
