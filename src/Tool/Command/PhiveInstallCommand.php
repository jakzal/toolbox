<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class PhiveInstallCommand implements Command
{
    private $alias;
    private $bin;
    private $sig;

    public function __construct(string $alias, string $bin, ?string $sig = null)
    {
        $this->alias = $alias;
        $this->bin = $bin;
        $this->sig = $sig;
    }

    public function __toString(): string
    {
        $home = \dirname($this->bin);
        $tmp = '/tmp/'.\md5($this->alias);

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
