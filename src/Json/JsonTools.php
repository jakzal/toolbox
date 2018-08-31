<?php declare(strict_types=1);

namespace Zalas\Toolbox\Json;

use InvalidArgumentException;
use RuntimeException;
use Zalas\Toolbox\Json\Factory\ToolFactory;
use Zalas\Toolbox\Tool\Collection;
use Zalas\Toolbox\Tool\Tool;
use Zalas\Toolbox\Tool\Tools;

final class JsonTools implements Tools
{
    /**
     * @var callable
     */
    private $resourceLocator;

    public function __construct(callable $resourceLocator)
    {
        $this->resourceLocator = $resourceLocator;
    }

    /**
     * @return Collection|Tool[]
     */
    public function all(): Collection
    {
        return \array_reduce($this->resources(), function (Collection $tools, string $resource): Collection {
            return $tools->merge(Collection::create(
                \array_map(\sprintf('%s::import', ToolFactory::class), $this->loadJson($resource))
            ));
        }, Collection::create([]));
    }

    private function loadJson(string $resource): array
    {
        $json = \json_decode(\file_get_contents($resource), true);

        if (!$json) {
            throw new RuntimeException(\sprintf('Failed to parse json: "%s"', $resource));
        }

        if (!isset($json['tools']) || !\is_array($json['tools'])) {
            throw new RuntimeException(\sprintf('Failed to find any tools in: "%s".', $resource));
        }

        return $json['tools'];
    }

    private function resources(): array
    {
        $resources = \call_user_func($this->resourceLocator);

        return \array_map(function (string $resource) {
            if (!\is_readable($resource)) {
                throw new InvalidArgumentException(\sprintf('Could not read the file: "%s".', $resource));
            }

            return $resource;
        }, $resources);
    }
}
