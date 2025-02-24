<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class PhiveInstallCommand implements Command
{
    private string $alias;
    private string $bin;
    private ?string $sig;

    public function __construct(string $alias, string $bin, ?string $sig = null)
    {
        $this->alias = $alias;
        $this->bin = $bin;
        $this->sig = $sig;
    }

    public function __toString(): string
    {
        $home = \sprintf('%s/.phive', \dirname($this->bin));
        $tmp = \sprintf('%s/tmp/%s', $home, \md5($this->alias));

        return \sprintf(
            'phive --no-progress --home %s install %s %s -t %s && mv %s/* %s',
            $home,
            $this->sig ? '--trust-gpg-keys '.$this->sig : '--force-accept-unsigned',
            $this->alias,
            $tmp,
            $tmp,
            $this->bin
        );
    }
}
