<?php declare(strict_types=1);

namespace Zalas\Toolbox\UseCase;

use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Filter;
use Zalas\Toolbox\Tool\Tools;

class ListTools
{
    private Tools $tools;

    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function __invoke(Filter $filter): Collection
    {
        return $this->tools->all($filter);
    }
}
