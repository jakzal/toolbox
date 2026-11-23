<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Metadata;

class MetadataTest extends TestCase
{
    private string $tempDir;

    protected function setUp(): void
    {
        $this->tempDir = \sys_get_temp_dir().'/metadata-test-'.\uniqid();
        \mkdir($this->tempDir);
    }

    protected function tearDown(): void
    {
        $files = \glob($this->tempDir.'/*');
        foreach ($files as $file) {
            \unlink($file);
        }
        \rmdir($this->tempDir);
    }

    public function test_it_loads_from_metadata_json_when_present()
    {
        \file_put_contents($this->tempDir.'/metadata.json', \json_encode([
            'php_versions' => ['8.2', '8.3', '8.4'],
        ]));

        $metadata = Metadata::load($this->tempDir);

        $this->assertSame(['8.2', '8.3', '8.4'], $metadata->phpVersions);
    }

    public function test_it_falls_back_to_composer_json_when_metadata_is_missing()
    {
        \file_put_contents($this->tempDir.'/composer.json', \json_encode([
            'require' => [
                'php' => '~8.3.0 || ~8.4.0 || ~8.5.0',
            ],
        ]));

        $metadata = Metadata::load($this->tempDir);

        $this->assertSame(['8.3', '8.4', '8.5'], $metadata->phpVersions);
    }

    public function test_it_prefers_metadata_json_over_composer_json()
    {
        \file_put_contents($this->tempDir.'/metadata.json', \json_encode([
            'php_versions' => ['8.0', '8.1'],
        ]));
        \file_put_contents($this->tempDir.'/composer.json', \json_encode([
            'require' => [
                'php' => '~8.3.0 || ~8.4.0',
            ],
        ]));

        $metadata = Metadata::load($this->tempDir);

        $this->assertSame(['8.0', '8.1'], $metadata->phpVersions);
    }
}
