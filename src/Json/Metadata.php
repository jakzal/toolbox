<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json;

final class Metadata
{
    public readonly array $phpVersions;

    private function __construct(array $phpVersions)
    {
        $this->phpVersions = $phpVersions;
    }

    public static function load(string $projectDir): self
    {
        $metadataPath = $projectDir.'/metadata.json';
        if (\file_exists($metadataPath)) {
            $data = \json_decode(\file_get_contents($metadataPath), true);

            return new self($data['php_versions']);
        }

        $composer = \json_decode(\file_get_contents($projectDir.'/composer.json'), true);

        return new self(PhpVersionsParser::parse($composer['require']['php']));
    }
}
