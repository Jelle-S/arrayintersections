<?php

namespace Jelle_S\Test\Util\Intersections;

use Jelle_S\Util\Intersections\ArrayIntersections;
use PHPUnit\Framework\TestCase;

/**
 * Tests \Jelle_S\Util\Intersections\ArrayIntersections.
 *
 * @author Jelle Sebreghts
 */
class ArrayKeyIntersectionsTest extends TestCase
{
    /**
     * Test getAll.
     */
    public function testGetAll()
    {
        $arrays = $this->getArrays();
        foreach ([3, 4, 5] as $threshold) {
            $intersections = new ArrayIntersections($arrays, $threshold);
            $result = $intersections->getAll();
            $this->assertTrue($this->hasValidThresholds($result, $threshold));
        }
    }

    /**
     * Test getLargest.
     */
    public function testLargest()
    {
        $arrays = $this->getArrays();
        $intersections = new ArrayIntersections($arrays, 3);
        $all = $intersections->getAll();
        $largest = $intersections->getLargest();
        $largestFound = [];
        foreach ($all as $array) {
            if (count($largestFound) < count($array)) {
                $largestFound = $array;
            }
        }
        $this->assertEquals(count($largestFound), count($largest));
    }

    /**
     * Test getting intersections for just one array.
     */
    public function testOneArray()
    {
        $arrays = [range(0, 10)];
        // Array bigger than threshold.
        $intersections = new ArrayIntersections($arrays, 3);
        $this->assertEquals($arrays, $intersections->getAll());

        // Array smaller than threshold.
        $intersections = new ArrayIntersections($arrays, 15);
        $this->assertEmpty($intersections->getAll());
    }

    protected function hasValidThresholds($arrays, $threshold)
    {
        foreach ($arrays as $array) {
            if (count($array) < $threshold) {
                return false;
            }
        }

        return true;
    }

    protected function getArrays()
    {
        return [
      0 => [
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 9,
        'f' => 10,
      ],
      1 => [
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 9,
        'g' => 17,
      ],
      2 => [
        'a' => 1,
        'b' => 42,
        'c' => 3,
        'd' => 4,
      ],
      3 => [
        'b' => 42,
        'c' => 3,
        'a' => 1,
      ],
      4 => [
        'z' => 26,
        'e' => 9,
        'a' => 1,
      ],
      'identical1' => [
        'abc' => 123,
      ],
      'identical2' => [
        'abc' => 123,
      ],
    ];
    }
}
