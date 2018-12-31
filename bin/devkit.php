#!/usr/bin/env php
<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command as CliCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Zalas\Toolbox\Runner\PassthruRunner;
use Zalas\Toolbox\Json\JsonTools;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;

$application = new Application('Toolbox DevKit', 'dev');
$application->add(
    new class extends CliCommand
    {
        protected function configure()
        {
            $this->setName('update:readme');
            $this->setDescription('Updates README.md with latest list of available tools');
            $this->addOption('tools', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Path(s) to the list of tools. Can also be set with TOOLBOX_JSON environment variable.', $this->toolsJsonDefault());
            $this->addOption('readme', null, InputOption::VALUE_REQUIRED, 'Path to the readme file', __DIR__.'/../README.md');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $jsonPath = $input->getOption('tools');
            $readmePath = $input->getOption('readme');
            $tools = (new JsonTools(function () use ($jsonPath) { return $jsonPath; }))->all(new Filter([], []));
            $toolsList = $tools->reduce('', function ($acc, Tool $tool) {
                return $acc . sprintf('* %s - [%s](%s)', $tool->name(), $tool->summary(), $tool->website()) . PHP_EOL;
            });

            $readme = file_get_contents($readmePath);
            $readme = preg_replace('/(## Available tools\n\n).*?(\n## Installation)/smi', '$1' . $toolsList . '$2', $readme);

            file_put_contents($readmePath, $readme);

            $output->writeln(sprintf('The <info>%s</info> was updated with latest tools found in <info>%s</info>.', $readmePath, implode(', ', $jsonPath)));
        }

        private function toolsJsonDefault(): array
        {
            return \getenv('TOOLBOX_JSON')
                ? \array_map('trim', \explode(',', \getenv('TOOLBOX_JSON')))
                : [__DIR__.'/../resources/pre-installation.json', __DIR__.'/../resources/tools.json'];
        }
    }
);
$application->add(
    new class extends CliCommand
    {
        protected function configure()
        {
            $this->setName('update:phars');
            $this->setDescription('Attempts to update phar links to latest versions');
            $this->addOption('tools', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Path(s) to the list of tools. Can also be set with TOOLBOX_JSON environment variable.', $this->toolsJsonDefault());
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            foreach ($input->getOption('tools') as $jsonPath) {
                $result = $this->updatePhars($jsonPath, $output);

                if ($result !== 0) {
                    return $result;
                }
            }

            return 0;
        }

        private function updatePhars(string $jsonPath, OutputInterface $output)
        {
            $phars = $this->findLatestPhars($jsonPath);

            if (empty($phars)) {
                return 0;
            }

            $output->writeln('Found phars:');

            foreach ($phars as $phar) {
                $output->writeln(sprintf('* %s', $phar));
            }

            $output->writeln(sprintf('Updated <info>%s</info>.', $jsonPath));

            return (new PassthruRunner())->run($this->updatePharsCommand($jsonPath, $phars));
        }

        private function findLatestPharsCommand(string $jsonPath): Command
        {
            $command = <<<'CMD'
            grep -e 'github\.com.*releases.*\.phar"' %TOOLBOX_JSON% |
            sed -e 's@.*github.com/\(.*\)/releases.*@\1@' |
            xargs -I"{}" sh -c "curl -s -XGET 'https://api.github.com/repos/{}/releases/latest' -H 'Accept:application/json' | grep browser_download_url | head -n 1" |
            sed -e 's/^[^:]*: "\([^"]*\)"/\1/'
CMD;
            $command = strtr($command, ['%TOOLBOX_JSON%' => $jsonPath]);

            return new ShCommand($command);
        }

        private function findLatestPhars(string $jsonPath): array
        {
            $phars = [];

            exec((string)$this->findLatestPharsCommand($jsonPath), $phars);

            return $phars;
        }

        private function updatePharsCommand(string $jsonPath, array $phars): Command
        {
            $replacements = implode(' ', array_map(
                function (string $phar) {
                    $project = preg_replace('@https://[^/]*/([^/]*/[^/]*).*@', '$1', $phar);

                    return strtr(
                        '-e "s@\"phar\": \"\([^\"]*%PROJECT%[^\"]*\)\"@\"phar\": \"%PHAR%\"@"',
                        ['%PROJECT%' => $project, '%PHAR%' => $phar]
                    );
                },
                $phars
            ));

            return new ShCommand(sprintf('sed -i.bak %s %s', $replacements, $jsonPath));
        }

        private function toolsJsonDefault(): array
        {
            return \getenv('TOOLBOX_JSON')
                ? \array_map('trim', \explode(',', \getenv('TOOLBOX_JSON')))
                : [__DIR__.'/../resources/pre-installation.json', __DIR__.'/../resources/tools.json'];
        }
    }
);
$application->run();