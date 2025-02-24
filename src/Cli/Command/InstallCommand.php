<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\UseCase\InstallTools;

final class InstallCommand extends Command
{
    use DefaultTag;
    use DefaultTargetDir;

    public const NAME = 'install';

    private InstallTools $useCase;
    private Runner $runner;

    public function __construct(InstallTools $useCase, Runner $runner)
    {
        parent::__construct(self::NAME);

        $this->useCase = $useCase;
        $this->runner = $runner;
    }

    protected function configure(): void
    {
        $this->setDescription('Installs tools');
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Output the command without executing it');
        $this->addOption('target-dir', null, InputOption::VALUE_REQUIRED, 'The target installation directory', $this->defaultTargetDir());
        $this->addOption('exclude-tag', 'e', InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY, 'Tool tags to exclude', $this->defaultExcludeTag());
        $this->addOption('tag', 't', InputOption::VALUE_REQUIRED|InputOption::VALUE_IS_ARRAY, 'Tool tags to filter by', $this->defaultTag());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return $this->runner->run(\call_user_func($this->useCase, new Filter($input->getOption('exclude-tag'), $input->getOption('tag'))));
    }
}
