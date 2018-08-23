<?php declare(strict_types=1);

namespace Zalas\Toolbox\Tests\Tool\Command;

use PHPUnit\Framework\TestCase;
use Zalas\Toolbox\Tool\Command\Assert;

class AssertTest extends TestCase
{
    public function test_it_throws_an_exception_if_a_field_is_missing()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing fields "b, d" in the Test: `{"a":"A","c":"C"}`.');

        Assert::requireFields(['a', 'b', 'c', 'd'], ['a' => 'A', 'c' => 'C'], 'Test');
    }
}
