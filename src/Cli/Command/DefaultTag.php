<?php declare(strict_types=1);

namespace Zalas\Toolbox\Cli\Command;

trait DefaultTag
{
    private function defaultExcludeTag(): array
    {
        return \getenv('TOOLBOX_EXCLUDED_TAGS') ? \explode(',', \getenv('TOOLBOX_EXCLUDED_TAGS')) : [\sprintf('exclude-php:%s.%s', PHP_MAJOR_VERSION, PHP_MINOR_VERSION)];
    }

    private function defaultTag(): array
    {
        return \getenv('TOOLBOX_TAGS') ? \explode(',', \getenv('TOOLBOX_TAGS')) : [];
    }
}
