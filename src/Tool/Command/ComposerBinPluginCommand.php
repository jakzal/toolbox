<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class ComposerBinPluginCommand implements Command
{
    private $package;
    private $namespace;

    public function __construct(string $package, string $namespace)
    {
        $this->package = $package;
        $this->namespace = $namespace;
    }

    public function __toString(): string
    {
        return \sprintf('composer global bin %s require --no-suggest --prefer-dist --update-no-dev -n %s', $this->namespace, $this->package);
    }

    public static function import(array $command): Command
    {
        Assert::requireFields(['package', 'namespace'], $command, 'ComposerBinPluginCommand');

        return new self($command['package'], $command['namespace']);
    }

    public function package(): string
    {
        return $this->package;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }
}
