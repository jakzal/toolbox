<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\UseCase\InstallTools;

final class InstallCommand extends Command
{
    public const NAME = 'install';

    private $useCase;
    private $runner;

    public function __construct(InstallTools $useCase, Runner $runner)
    {
        parent::__construct(self::NAME);

        $this->useCase = $useCase;
        $this->runner = $runner;
    }

    protected function configure()
    {
        $this->setDescription('Installs tools');
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Output the command without executing it');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->runner->run(\call_user_func($this->useCase));
    }
}
