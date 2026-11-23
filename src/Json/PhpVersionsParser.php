<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json;

final class PhpVersionsParser
{
    public static function parse(string $constraint): array
    {
        \preg_match_all('/(?:~|\^|>=?)?\s*(\d+\.\d+)/', $constraint, $m);
        $versions = \array_unique($m[1]);
        \usort($versions, 'version_compare');

        return $versions;
    }
}
