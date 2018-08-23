<?php declare(strict_types=1);

namespace Zalas\Toolbox\UseCase;

use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;

class ListTools
{
    /**
     * @var Tools
     */
    private $tools;

    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    /**
     * @return Collection|Tool[]
     */
    public function __invoke(): Collection
    {
        return $this->tools->all();
    }
}
