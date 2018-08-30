<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json\Factory;

final class Assert
{
    public static function requireFields(array $fields, array $data, string $type)
    {
        $missingFields = \array_filter($fields, function (string $field) use ($data) {
            return !isset($data[$field]);
        });

        if (!empty($missingFields)) {
            throw new \InvalidArgumentException(\sprintf('Missing fields "%s" in the %s: `%s`.', \implode(', ', $missingFields), $type, \json_encode($data)));
        }
    }
}
