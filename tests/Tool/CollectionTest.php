<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tool;

use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function test_it_iterates_over_its_elements()
    {
        $elements = [1, 2, 3];

        $c = Collection::create($elements);

        $this->assertIterates($elements, $c);
    }

    public function test_it_is_cast_to_an_array()
    {
        $elements = [1, 2, 3];

        $c = Collection::create($elements);

        $this->assertSame($elements, $c->toArray());
    }

    public function test_it_merges_two_collections()
    {
        $c1 = Collection::create([1, 2, 3]);
        $c2 = Collection::create([4, 5]);

        $c = $c1->merge($c2);

        $this->assertNotSame($c1, $c, 'merge() creates a new collection');
        $this->assertNotSame($c2, $c, 'merge() creates a new collection');
        $this->assertIterates([1, 2, 3, 4, 5], $c);
    }

    public function test_it_filters_elements_in_the_collection()
    {
        $c = Collection::create([1, 2, 3, 4]);
        $filtered = $c->filter(function (int $e) {
            return 0 === $e % 2;
        });

        $this->assertNotSame($c, $filtered, 'filter() creates a new collection');
        $this->assertIterates([2, 4], $filtered);
    }

    public function test_it_maps_elements_in_the_collection()
    {
        $c = Collection::create([1, 2, 3, 4]);
        $mapped = $c->map(function (int $e) {
            return $e * 2;
        });

        $this->assertNotSame($c, $mapped, 'map() creates a new collection');
        $this->assertIterates([2, 4, 6, 8], $mapped);
    }

    public function test_it_folds_the_collection_left()
    {
        $c = Collection::create(['a', 'b', 'c']);
        $reduced = $c->reduce('d', function (string $a, string $b): string {
            return $a.$b;
        });

        $this->assertNotSame($c, $reduced, 'reduce() creates a new collection');
        $this->assertSame('dabc', $reduced);
    }

    public function test_it_counts_its_elements()
    {
        $this->assertSame(3, Collection::create(['a', 'b', 'c'])->count());
        $this->assertSame(3, \count(Collection::create(['a', 'b', 'c'])));
    }

    private function assertIterates(array $elements, Collection $c)
    {
        $this->assertSame($elements, \iterator_to_array($c));
    }
}
