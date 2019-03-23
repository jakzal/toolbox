<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;

final class ComposerBinPluginCommand implements Command
{
    private $package;

    private $namespace;

    private $links;

    public function __construct(string $package, string $namespace, Collection $links)
    {
        $this->package = $package;
        $this->namespace = $namespace;
        $this->links = $links;
    }

    public function __toString(): string
    {
        return \sprintf('composer global bin %s require --no-suggest --prefer-dist --update-no-dev -n %s%s', $this->namespace, $this->package, $this->linkCommand());
    }

    public function package(): string
    {
        return $this->package;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function links(): Collection
    {
        return $this->links;
    }

    private function linkCommand(): string
    {
        return $this->links->reduce('', function (string $command, ComposerBinPluginLinkCommand $link) {
            return $command.' && '.$link;
        });
    }
}
