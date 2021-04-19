<?php declare(strict_types=1);

return [
    'patchers' => [
        function (string $filePath, string $prefix, string $content): string {
            if (!Strings::contains($filePath, 'vendor/')) {
                return $content;
            }

            $fqcnReservedPattern = sprintf('#(\\\\)?%s\\\\(parent|self|static)#m', $prefix);
            $matches = Strings::matchAll($content, $fqcnReservedPattern);

            if (!$matches) {
                return $content;
            }

            foreach ($matches as $match) {
                $content = str_replace($match[0], $match[2], $content);
            }

            return $content;
        },
    ],
];
