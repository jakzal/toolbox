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
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tool;

trait Tools
{
    private function toolsJsonDefault(): array
    {
        return \getenv('TOOLBOX_JSON')
            ? \array_map('trim', \explode(',', \getenv('TOOLBOX_JSON')))
            : [__DIR__ . '/../resources/pre-installation.json', __DIR__ . '/../resources/tools.json'];
    }

    /**
     * @return Collection|Tool[]
     */
    private function loadTools($jsonPath, ?Filter $filter = null): Collection
    {
        return (new JsonTools(function () use ($jsonPath) {
            return $jsonPath;
        }))->all($filter ?? new Filter([], []));
    }
}

$application = new Application('Toolbox DevKit', 'dev');
$application->add(
    new class extends CliCommand
    {
        use Tools;

        protected function configure()
        {
            $this->setName('update:readme');
            $this->setDescription('Updates README.md with latest list of available tools');
            $this->addOption('tools', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Path(s) to the list of tools. Can also be set with TOOLBOX_JSON environment variable.', $this->toolsJsonDefault());
            $this->addOption('readme', null, InputOption::VALUE_REQUIRED, 'Path to the readme file', __DIR__ . '/../README.md');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $jsonPath = $input->getOption('tools');
            $readmePath = $input->getOption('readme');
            $tools = $this->loadTools($jsonPath);
            $toolsList = $tools->reduce('', function ($acc, Tool $tool) {
                return $acc . sprintf('* %s - [%s](%s)', $tool->name(), $tool->summary(), $tool->website()) . PHP_EOL;
            });

            $readme = file_get_contents($readmePath);
            $readme = preg_replace('/(## Available tools\n\n).*?(\n## )/smi', '$1' . $toolsList . '$2', $readme);

            file_put_contents($readmePath, $readme);

            $output->writeln(sprintf('The <info>%s</info> was updated with latest tools found in <info>%s</info>.', $readmePath, implode(', ', $jsonPath)));
        }
    }
);
$application->add(
    new class extends CliCommand
    {
        use Tools;

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
    }
);
$application->add(
    new class extends CliCommand
    {
        use Tools;

        protected function configure()
        {
            $this->setName('generate:html');
            $this->setDescription('Generates an html list of available tools');
            $this->addOption('tools', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Path(s) to the list of tools. Can also be set with TOOLBOX_JSON environment variable.', $this->toolsJsonDefault());
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $tools = $this->loadTools($input->getOption('tools'), new Filter([\Zalas\Toolbox\UseCase\InstallTools::PRE_INSTALLATION_TAG], []));

            $output->writeln($this->renderPage($tools->map($this->toolToHtml())));
        }

        private function toolToHtml(): \Closure
        {
            $tagTemplate = '<li class="list-inline-item"><span class="badge badge-primary">%TAG%</span></li>';
            $toolTemplate = <<<'TEMPLATE'
<div class="card m-3">
    <div class="card-body">
        <h5 class="card-title">%NAME%</h5>
        <p class="card-text tool-summary">%SUMMARY%</p>
        <a href="%WEBSITE%" class="card-link" title="%NAME%">%WEBSITE_NAME%</a>
    </div>
    <div class="card-footer">
        <ul class="list-inline mb-1">
            %TAGS%
        </ul>
    </div>
</div>
TEMPLATE;

            return function (Tool $tool) use ($toolTemplate, $tagTemplate) {
                return strtr(
                    $toolTemplate,
                    [
                        '%NAME%' => $tool->name(),
                        '%SUMMARY%' => $tool->summary(),
                        '%WEBSITE%' => $tool->website(),
                        '%WEBSITE_NAME%' => preg_replace('#^(https?://(github.com/)?)(.*?)/?$#', '$3', $tool->website()),
                        '%TAGS%' => \implode(\array_map(function (string $tag) use ($tagTemplate) {
                            return strtr($tagTemplate, ['%TAG%' => $tag]);
                        }, $tool->tags()))
                    ]);
            };
        }

        /**
         * @param Collection|string[] $toolsHtml
         * @return string
         */
        private function renderPage(Collection $toolsHtml): string
        {
            $template = <<<'TEMPLATE'
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <title>Static and Quality Analysis Tools for PHP | Toolbox | PHPQA</title>
    <style>
        .tool-summary {
            font-size: 0.8em;
            height: 2em;
        }
        .card-link {
            font-size: 0.7em;
        }
    </style>
</style>
</head>
<body>
<div class="container-fluid p-5">

    <div class="jumbotron">
        <h1 class="display-4">Static and Quality Analysis Tools for PHP</h1>
        <p class="lead">
          The below list of tools is provided by the <a href="https://hub.docker.com/r/jakzal/phpqa/">phpqa</a> docker image.
          <a href="https://github.com/jakzal/toolbox">Toolbox</a> is used to install them in the image.
        </p>
        <hr class="my-4">
        <a class="btn btn-primary btn-lg" href="https://github.com/jakzal/toolbox" role="button">toolbox repository</a>
        <a class="btn btn-primary btn-lg" href="https://hub.docker.com/r/jakzal/phpqa/" role="button">phpqa docker image</a>
        <a class="btn btn-primary btn-lg" href="https://github.com/jakzal/phpqa" role="button">phpqa repository</a>
    </div>

    %TOOLS%

</body>
</html>
TEMPLATE;

            return strtr($template, [
                '%TOOLS%' => \implode(PHP_EOL, \array_map(
                    function ($htmls) {
                        return PHP_EOL . '<div class="card-deck">' . implode($htmls) . '</div>' . PHP_EOL;
                    },
                    \array_chunk($toolsHtml->toArray(), 4)
                )),
            ]);
        }
    }
);
$application->run();