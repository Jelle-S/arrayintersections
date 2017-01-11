<?php

namespace Jelle_S\Test\Util\Intersections;

use PHPUnit\Framework\TestCase;
use Jelle_S\Util\Intersections\ArrayIntersections;

/**
 * Tests \Jelle_S\Util\Intersections\ArrayIntersections.
 *
 * @author Jelle Sebreghts
 */
class IntersectionsTest extends TestCase {

  /**
   * Test getAll.
   */
  public function testGetAll() {
    $arrays = $this->getArrays();
    foreach (array(3, 4, 5) as $threshold) {
      $intersections = new ArrayIntersections($arrays, $threshold);
      $result = $intersections->getAll();
      $this->assertTrue($this->hasValidThresholds($result, $threshold));
    }
  }

  /**
   * Test getLargest.
   */
  public function testLargest() {
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

  protected function hasValidThresholds($arrays, $threshold) {
    foreach ($arrays as $array) {
      if (count($array) < $threshold) {
        return FALSE;
      }
    }
    return TRUE;
  }

  protected function getArrays() {
    return array(
      0 => array(
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 9,
        'f' => 10,
      ),
      1 => array(
        'a' => 1,
        'b' => 2,
        'c' => 3,
        'd' => 4,
        'e' => 9,
        'g' => 17,
      ),
      2 => array(
        'a' => 1,
        'b' => 42,
        'c' => 3,
        'd' => 4,
      ),
      3 => array(
        'b' => 42,
        'c' => 3,
        'a' => 1,
      ),
      4 => array(
        'z' => 26,
        'e' => 9,
        'a' => 1,
      ),
      'identical1' => array(
        'abc' => 123,
      ),
      'identical2' => array(
        'abc' => 123,
      )
    );
  }

}
