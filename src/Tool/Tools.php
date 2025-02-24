<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

use RuntimeException;

interface Tools
{
    /**
     * @param Filter $filter
     * @return Collection
     * @throws RuntimeException in case tools cannot be loaded
     */
    public function all(Filter $filter): Collection;
}
