<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Json\Factory;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Json\Factory\ToolFactory;
use Zalas\Toolbox\Tool\Command;
use Zalas\Toolbox\Tool\Command\BoxBuildCommand;
use Zalas\Toolbox\Tool\Command\ComposerBinPluginCommand;
use Zalas\Toolbox\Tool\Command\ComposerGlobalInstallCommand;
use Zalas\Toolbox\Tool\Command\ComposerInstallCommand;
use Zalas\Toolbox\Tool\Command\FileDownloadCommand;
use Zalas\Toolbox\Tool\Command\MultiStepCommand;
use Zalas\Toolbox\Tool\Command\PharDownloadCommand;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Command\TestCommand;

class ToolFactoryTest extends TestCase
{
    public function test_it_imports_tool_definition_from_an_array()
    {
        $tool = ToolFactory::import([
            'name' => 'phpstan',
            'summary' => 'Static analysis tool',
            'website' => 'https://github.com/phpstan/phpstan',
            'command' => [
                'composer-bin-plugin' => [
                    'package' => 'phpstan/phpstan',
                    'namespace' => 'tools'
                ]
            ],
            'test' => '/usr/bin/true',
            'tags' => ['qa', 'static-analysis'],
        ]);

        $this->assertSame('phpstan', $tool->name());
        $this->assertSame('Static analysis tool', $tool->summary());
        $this->assertSame('https://github.com/phpstan/phpstan', $tool->website());
        $this->assertSame(['qa', 'static-analysis'], $tool->tags());
        $this->assertInstanceOf(Command::class, $tool->command());
        $this->assertInstanceOf(TestCommand::class, $tool->testCommand());
    }

    public function test_it_imports_the_composer_bin_plugin_command()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'composer-bin-plugin' => [
                    'package' => 'phpstan/phpstan',
                    'namespace' => 'tools'
                ]
            ]
        ]));

        $this->assertInstanceOf(ComposerBinPluginCommand::class, $tool->command());
    }

    public function test_it_imports_the_phar_download_command()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'phar-download' => [
                    'phar' => 'phpstan/phpstan',
                    'bin' => 'tools'
                ]
            ]
        ]));

        $this->assertInstanceOf(PharDownloadCommand::class, $tool->command());
    }

    public function test_it_imports_the_file_download_command()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'file-download' => [
                    'url' => 'http://example.com/file',
                    'file' => 'file'
                ]
            ]
        ]));

        $this->assertInstanceOf(FileDownloadCommand::class, $tool->command());
    }

    public function test_it_imports_the_box_build_command()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'box-build' => [
                    'repository' => 'https://github.com/behat/behat.git',
                    'phar' => 'behat.phar',
                    'bin' => '/usr/local/bin/behat',
                    'version' => 'v3.4.0',
                ]
            ]
        ]));

        $this->assertInstanceOf(BoxBuildCommand::class, $tool->command());
    }

    public function test_it_imports_the_composer_install_command()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'composer-install' => [
                    'repository' => 'https://github.com/behat/behat.git',
                    'version' => 'v3.4.0',
                ]
            ]
        ]));

        $this->assertInstanceOf(ComposerInstallCommand::class, $tool->command());
    }

    public function test_it_imports_the_composer_global_install_command()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'composer-global-install' => [
                    'package' => 'behat/behat',
                    'version' => 'v3.4.0',
                ]
            ]
        ]));

        $this->assertInstanceOf(ComposerGlobalInstallCommand::class, $tool->command());
    }

    public function test_it_imports_the_sh_command()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'sh' => [
                    'command' => 'echo "42"',
                ]
            ]
        ]));

        $this->assertInstanceOf(ShCommand::class, $tool->command());
    }

    public function test_it_imports_multiple_commands()
    {
        $tool = ToolFactory::import($this->definition([
            'command' => [
                'phar-download' => [
                    'phar' => 'phpstan/phpstan',
                    'bin' => 'tools'
                ],
                'file-download' => [
                    [
                        'url' => 'http://example.com/file1',
                        'file' => 'file1'
                    ],
                    [
                        'url' => 'http://example.com/file2',
                        'file' => 'file2'
                    ]
                ]
            ]
        ]));

        $this->assertInstanceOf(MultiStepCommand::class, $tool->command());
    }

    public function test_it_complains_if_it_cannot_recognise_the_command()
    {
        $this->expectException(\RuntimeException::class);

        ToolFactory::import($this->definition(['command' => ['foo' => ['phar' => 'phpstan/phpstan']]]));
    }

    public function test_it_complains_if_the_command_is_empty()
    {
        $this->expectException(\RuntimeException::class);

        ToolFactory::import($this->definition(['command' => []]));
    }

    /**
     * @dataProvider provideRequiredProperties
     */
    public function test_it_complains_if_any_of_required_properties_is_missing(string $property)
    {
        $this->expectException(\InvalidArgumentException::class);

        $properties = $this->definition();

        unset($properties[$property]);

        ToolFactory::import($properties);
    }

    public function provideRequiredProperties(): \Generator
    {
        yield ['name'];
        yield ['summary'];
        yield ['website'];
        yield ['command'];
        yield ['test'];
    }

    private function definition(array $overrides = []): array
    {
        return \array_merge(
            [
                'name' => 'phpstan',
                'summary' => 'Static analysis tool',
                'website' => 'https://github.com/phpstan/phpstan',
                'command' => [
                    'composer-bin-plugin' => [
                        'package' => 'phpstan/phpstan',
                        'namespace' => 'tools'
                    ]
                ],
                'test' => '/usr/bin/true',
            ],
            $overrides
        );
    }
}
