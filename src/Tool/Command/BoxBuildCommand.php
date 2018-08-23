<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class BoxBuildCommand implements Command
{
    private $repository;
    private $phar;
    private $bin;
    private $version;
    
    private function __construct(string $repository, string $phar, string $bin, ?string $version = null)
    {
        $this->repository = $repository;
        $this->phar = $phar;
        $this->bin = $bin;
        $this->version = $version;
    }

    public function __toString(): string
    {
        return \sprintf(
            'cd /tools && git clone %s && cd /tools/%s && git checkout %s && composer install --no-dev --no-suggest --prefer-dist -n && box build && mv %s %s && chmod +x %s && cd && rm -rf /tools/%s',
            $this->repository,
            $this->targetDir(),
            $this->version ?? '$(git describe --tags $(git rev-list --tags --max-count=1) 2>/dev/null)',
            $this->phar,
            $this->bin,
            $this->bin,
            $this->targetDir()
        );
    }

    public static function import(array $definition): Command
    {
        Assert::requireFields(['repository', 'phar', 'bin'], $definition, 'BoxBuildCommand');

        return new self($definition['repository'], $definition['phar'], $definition['bin'], $definition['version'] ?? null);
    }

    private function targetDir(): string
    {
        $targetDir = \preg_replace('#^.*/(.*?)(.git)?$#', '$1', $this->repository);

        return  $targetDir !== $this->repository ? $targetDir : 'tmp';
    }
}
