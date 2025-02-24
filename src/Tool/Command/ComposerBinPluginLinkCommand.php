<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class ComposerBinPluginLinkCommand implements Command
{
    private string $source;
    private string $target;
    private string $namespace;

    public function __construct(string $source, string $target, string $namespace)
    {
        $this->source = $source;
        $this->target = $target;
        $this->namespace = $namespace;
    }

    public function __toString(): string
    {
        return \sprintf(
            'ln -sf ${COMPOSER_HOME:-"~/.composer"}/vendor-bin/%s/vendor/bin/%s %s',
            $this->namespace,
            $this->source,
            $this->target
        );
    }
}
