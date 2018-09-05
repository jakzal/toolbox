<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Runner;

use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Runner\Runner;
use Zalas\Toolbox\Tool\Command;

final class DryRunner implements Runner
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function run(Command $command): int
    {
        $this->output->writeln((string) $command);

        return 0;
    }
}
