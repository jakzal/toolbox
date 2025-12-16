<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Zalas\Toolbox\Json\PhpVersionsParser;

class PhpVersionsParserTest extends TestCase
{
    public function test_it_parses_tilde_constraints()
    {
        $versions = PhpVersionsParser::fromConstraint('~8.2.0 || ~8.3.0 || ~8.4.0');

        $this->assertSame(['8.2', '8.3', '8.4'], $versions);
    }

    public function test_it_parses_caret_constraints()
    {
        $versions = PhpVersionsParser::fromConstraint('^8.2 || ^8.3');

        $this->assertSame(['8.2', '8.3'], $versions);
    }

    public function test_it_parses_comparison_constraints()
    {
        $versions = PhpVersionsParser::fromConstraint('>=8.2.0');

        $this->assertSame(['8.2'], $versions);
    }

    public function test_it_parses_wildcard_constraints()
    {
        $versions = PhpVersionsParser::fromConstraint('8.2.* || 8.3.*');

        $this->assertSame(['8.2', '8.3'], $versions);
    }

    public function test_it_parses_single_version()
    {
        $versions = PhpVersionsParser::fromConstraint('~8.4.0');

        $this->assertSame(['8.4'], $versions);
    }

    public function test_it_sorts_versions()
    {
        $versions = PhpVersionsParser::fromConstraint('~8.4.0 || ~8.2.0 || ~8.3.0');

        $this->assertSame(['8.2', '8.3', '8.4'], $versions);
    }

    public function test_it_removes_duplicate_versions()
    {
        $versions = PhpVersionsParser::fromConstraint('~8.2.0 || ^8.2 || >=8.2.0');

        $this->assertSame(['8.2'], $versions);
    }

    public function test_it_parses_mixed_constraint_formats()
    {
        $versions = PhpVersionsParser::fromConstraint('~7.4.0 || ^8.0 || >=8.1.0 || 8.2.*');

        $this->assertSame(['7.4', '8.0', '8.1', '8.2'], $versions);
    }

    public function test_it_throws_exception_for_invalid_constraint()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No valid PHP versions found in constraint');

        PhpVersionsParser::fromConstraint('invalid constraint');
    }

    public function test_it_parses_from_composer_file()
    {
        $tempFile = \tempnam(\sys_get_temp_dir(), 'composer');
        \file_put_contents($tempFile, \json_encode([
            'require' => [
                'php' => '~8.2.0 || ~8.3.0 || ~8.4.0'
            ]
        ]));

        $versions = PhpVersionsParser::fromComposerFile($tempFile);

        $this->assertSame(['8.2', '8.3', '8.4'], $versions);

        \unlink($tempFile);
    }

    public function test_it_throws_exception_when_composer_file_not_found()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Composer file not found');

        PhpVersionsParser::fromComposerFile('/nonexistent/composer.json');
    }

    public function test_it_throws_exception_when_composer_file_is_invalid_json()
    {
        $tempFile = \tempnam(\sys_get_temp_dir(), 'composer');
        \file_put_contents($tempFile, 'not valid json');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to parse composer file as JSON');

        PhpVersionsParser::fromComposerFile($tempFile);

        \unlink($tempFile);
    }

    public function test_it_throws_exception_when_php_constraint_is_missing()
    {
        $tempFile = \tempnam(\sys_get_temp_dir(), 'composer');
        \file_put_contents($tempFile, \json_encode([
            'require' => [
                'symfony/console' => '^6.0'
            ]
        ]));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No "require.php" constraint found');

        PhpVersionsParser::fromComposerFile($tempFile);

        \unlink($tempFile);
    }

    public function test_it_gets_minimum_version()
    {
        $versions = ['8.3', '8.2', '8.4'];

        $minVersion = PhpVersionsParser::getMinimumVersion($versions);

        $this->assertSame('8.2', $minVersion);
    }

    public function test_it_throws_exception_when_getting_minimum_from_empty_array()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Versions array cannot be empty');

        PhpVersionsParser::getMinimumVersion([]);
    }
}
