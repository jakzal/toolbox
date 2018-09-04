<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\UseCase\TestTools;

final class TestCommand extends Command
{
    public const NAME = 'test';

    private $useCase;
    private $runner;

    public function __construct(TestTools $useCase, Runner $runner)
    {
        parent::__construct(self::NAME);

        $this->useCase = $useCase;
        $this->runner = $runner;
    }

    protected function configure()
    {
        $this->setDescription('Runs basic tests to verify tools are installed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->runner->run(\call_user_func($this->useCase));
    }
}
