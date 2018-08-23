<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

use Countable;
use IteratorAggregate;
use Traversable;

class Collection implements IteratorAggregate, Countable
{
    /**
     * @var array
     */
    private $elements;

    private function __construct(array $elements)
    {
        $this->elements = $elements;
    }

    public static function create(array $elements): Collection
    {
        return new self($elements);
    }

    public function getIterator(): Traversable
    {
        yield from $this->elements;
    }

    public function merge(Collection $other): Collection
    {
        return self::create(\array_merge($this->elements, $other->elements));
    }

    public function filter(callable $f): Collection
    {
        return self::create(\array_values(\array_filter($this->elements, $f)));
    }

    public function map(callable $f): Collection
    {
        return self::create(\array_map($f, $this->elements));
    }

    public function reduce($initial, callable $param)
    {
        return \array_reduce($this->elements, $param, $initial);
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function count(): int
    {
        return \count($this->elements);
    }
}
