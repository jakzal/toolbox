<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class ComposerInstallCommand implements Command
{
    private $repository;
    private $version;

    public function __construct(string $repository, ?string $version = null)
    {
        $this->repository = $repository;
        $this->version = $version;
    }

    public function __toString(): string
    {
        return \sprintf(
            'cd /tools && git clone %s && cd /tools/%s && git checkout %s && composer install --no-dev --no-suggest --prefer-dist -n',
            $this->repository,
            $this->targetDir(),
            $this->version ?? '$(git describe --tags $(git rev-list --tags --max-count=1) 2>/dev/null)'
        );
    }

    private function targetDir(): string
    {
        $targetDir = \preg_replace('#^.*/(.*?)(.git)?$#', '$1', $this->repository);

        return  $targetDir !== $this->repository ? $targetDir : 'tmp';
    }
}
