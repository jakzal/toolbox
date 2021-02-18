<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool\Command;

use Zalas\Toolbox\Tool\Command;

final class PhiveInstallCommand implements Command
{
    private $alias;
    private $bin;
    private $trust;
    private $unsigned;

    public function __construct(string $alias, string $bin, bool $trust = true, bool $unsigned = false)
    {
        $this->alias = $alias;
        $this->bin = $bin;
        $this->trust = $trust;
        $this->unsigned = $unsigned;
    }

    public function __toString(): string
    {
        return \sprintf(
            'phive install %s %s %s -t %s',
            $this->trust ? '--trust-gpg-keys' : '',
            $this->unsigned ? '--force-accept-unsigned' : '',
            $this->alias,
            $this->bin
        );
    }
}
