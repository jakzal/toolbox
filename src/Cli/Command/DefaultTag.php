<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Command;

trait DefaultTag
{
    private function defaultExcludeTag(): array
    {
        return \getenv('TOOLBOX_EXCLUDED_TAGS') ? \explode(',', \getenv('TOOLBOX_EXCLUDED_TAGS')) : [];
    }

    private function defaultTag(): array
    {
        return \getenv('TOOLBOX_TAGS') ? \explode(',', \getenv('TOOLBOX_TAGS')) : [];
    }
}
