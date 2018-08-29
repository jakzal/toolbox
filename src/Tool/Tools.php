<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

use InvalidArgumentException;
use RuntimeException;
use Zalas\Toolbox\Tool\Command\ShCommand;
use Zalas\Toolbox\Tool\Command\TestCommand;

class Tools
{
    /**
     * @var string
     */
    private $resource;

    public function __construct(string $resource)
    {
        if (!\is_readable($resource)) {
            throw new InvalidArgumentException(\sprintf('Could not read the file: "%s".', $resource));
        }

        $this->resource = $resource;
    }

    /**
     * @return Collection|Tool[]
     */
    public function all(): Collection
    {
        return $this->defaultTools()
            ->merge(Collection::create(
                \array_map(\sprintf('%s::import', Tool::class), $this->loadJson())
            ));
    }

    private function loadJson(): array
    {
        $json = \json_decode(\file_get_contents($this->resource), true);

        if (!$json) {
            throw new RuntimeException(\sprintf('Failed to parse json: "%s"', $this->resource));
        }

        if (!isset($json['tools']) || !\is_array($json['tools'])) {
            throw new RuntimeException(\sprintf('Failed to find any tools in: "%s".', $this->resource));
        }

        return $json['tools'];
    }

    private function defaultTools(): Collection
    {
        return Collection::create([
            new Tool(
                'composer',
                'Dependency Manager for PHP',
                'https://getcomposer.org/',
                new ShCommand('composer self-update'),
                new TestCommand('composer list', 'composer')
            ),
            new Tool(
                'composer-bin-plugin',
                'Composer plugin to install bin vendors in isolated locations',
                'https://github.com/bamarni/composer-bin-plugin',
                new ShCommand('composer global require bamarni/composer-bin-plugin'),
                new TestCommand('composer global show bamarni/composer-bin-plugin', 'composer-bin-plugin')
            ),
            new Tool(
                'box',
                'An application for building and managing Phars',
                'https://box-project.github.io/box2/',
                new ShCommand('curl -Ls https://box-project.github.io/box2/installer.php | php && mv box.phar /usr/local/bin/box && chmod +x /usr/local/bin/box'),
                new TestCommand('box list', 'box')
            ),
        ]);
    }
}
