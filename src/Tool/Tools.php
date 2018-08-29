<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

use RuntimeException;

interface Tools
{
    /**
     * @return Collection|Tool[]
     * @throws RuntimeException in case tools cannot be loaded
     */
    public function all(): Collection;
}
