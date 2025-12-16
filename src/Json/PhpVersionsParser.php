<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json;

use InvalidArgumentException;
use RuntimeException;

final class PhpVersionsParser
{
    /**
     * Parses PHP versions from a composer.json file.
     *
     * @param string $composerJsonPath Path to composer.json
     * @return array<string> Array of PHP versions (e.g., ['8.2', '8.3', '8.4'])
     * @throws RuntimeException If file cannot be read or parsed
     * @throws InvalidArgumentException If PHP constraint is missing or invalid
     */
    public static function fromComposerFile(string $composerJsonPath): array
    {
        if (!\file_exists($composerJsonPath)) {
            throw new RuntimeException(\sprintf('Composer file not found: "%s"', $composerJsonPath));
        }

        $content = \file_get_contents($composerJsonPath);
        if ($content === false) {
            throw new RuntimeException(\sprintf('Failed to read composer file: "%s"', $composerJsonPath));
        }

        $json = \json_decode($content, true);
        if ($json === null) {
            throw new RuntimeException(\sprintf('Failed to parse composer file as JSON: "%s"', $composerJsonPath));
        }

        if (!isset($json['require']['php'])) {
            throw new InvalidArgumentException(\sprintf('No "require.php" constraint found in: "%s"', $composerJsonPath));
        }

        return self::fromConstraint($json['require']['php']);
    }

    /**
     * Parses PHP versions from a composer constraint string.
     *
     * @param string $constraint PHP version constraint (e.g., "~8.2.0 || ~8.3.0 || ~8.4.0")
     * @return array<string> Array of PHP versions (e.g., ['8.2', '8.3', '8.4'])
     * @throws InvalidArgumentException If constraint format is invalid
     */
    public static function fromConstraint(string $constraint): array
    {
        // Match tilde constraints like ~8.2.0, ~8.3.0, etc.
        // Also support caret (^8.2), >=8.2.0, 8.2.*, etc.
        $pattern = '/(?:~|\^|>=?)?\s*(\d+\.\d+)(?:\.\d+)?(?:\s*\.\*)?/';

        \preg_match_all($pattern, $constraint, $matches);

        if (empty($matches[1]) || empty(\array_filter($matches[1]))) {
            throw new InvalidArgumentException(\sprintf('No valid PHP versions found in constraint: "%s"', $constraint));
        }

        // Extract unique versions and sort them
        $versions = \array_unique($matches[1]);
        \usort($versions, 'version_compare');

        return \array_values($versions);
    }

    /**
     * Gets the minimum (lowest) version from an array of versions.
     *
     * @param array<string> $versions Array of version strings
     * @return string Minimum version
     * @throws InvalidArgumentException If versions array is empty
     */
    public static function getMinimumVersion(array $versions): string
    {
        if (empty($versions)) {
            throw new InvalidArgumentException('Versions array cannot be empty');
        }

        $sorted = $versions;
        \usort($sorted, 'version_compare');

        return $sorted[0];
    }
}
