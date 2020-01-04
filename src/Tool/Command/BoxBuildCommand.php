<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class BoxBuildCommand implements Command
{
    private $repository;
    private $phar;
    private $bin;
    private $workDir;
    private $version;

    public function __construct(string $repository, string $phar, string $bin, string $workDir, ?string $version = null)
    {
        $this->repository = $repository;
        $this->phar = $phar;
        $this->bin = $bin;
        $this->workDir = $workDir;
        $this->version = $version;
    }

    public function __toString(): string
    {
        return \sprintf(
            'git clone %s %s&& cd %s && git checkout %s && composer install --no-dev --no-suggest --prefer-dist -n && box-legacy build && mv %s %s && chmod +x %s && cd && rm -rf %s',
            $this->repository,
            $this->targetDir(),
            $this->targetDir(),
            $this->version ?? '$(git describe --tags $(git rev-list --tags --max-count=1) 2>/dev/null)',
            $this->phar,
            $this->bin,
            $this->bin,
            $this->targetDir()
        );
    }

    private function targetDir(): string
    {
        $targetDir = \preg_replace('#^.*/(.*?)(.git)?$#', '$1', $this->repository);

        return  \sprintf('%s/%s', $this->workDir, $targetDir !== $this->repository ? $targetDir : 'tmp');
    }
}
