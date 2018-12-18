<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class ComposerInstallCommand implements Command
{
    private $repository;
    private $targetDir;
    private $version;

    public function __construct(string $repository, string $targetDir, ?string $version = null)
    {
        $this->repository = $repository;
        $this->targetDir = $targetDir;
        $this->version = $version;
    }

    public function __toString(): string
    {
        return \sprintf(
            'git clone %s %s && cd %s && git checkout %s && composer install --no-dev --no-suggest --prefer-dist -n',
            $this->repository,
            $this->targetDir,
            $this->targetDir,
            $this->version ?? '$(git describe --tags $(git rev-list --tags --max-count=1) 2>/dev/null)'
        );
    }
}
