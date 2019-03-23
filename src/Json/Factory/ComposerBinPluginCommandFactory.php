<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginLinkCommand;

final class ComposerBinPluginCommandFactory
{
    public static function import(array $command): Command
    {
        Assert::requireFields(['package', 'namespace'], $command, 'ComposerBinPluginCommand');

        return new ComposerBinPluginCommand($command['package'], $command['namespace'], self::importLinks($command));
    }

    private static function importLinks(array $command): Collection
    {
        $links = $command['links'] ?? [];
        $namespace = $command['namespace'];

        return Collection::create(
            \array_map(function (string $source, string $target) use ($namespace) {
                return new ComposerBinPluginLinkCommand($source, $target, $namespace);
            }, \array_values($links), \array_keys($links))
        );
    }
}
