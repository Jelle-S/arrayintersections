<?php

namespace Jelle_S\Util\Intersections;

use Jelle_S\Util\BitMask\BitMaskGenerator;

/**
 * Tries to find intersections between a combination of arrays.
 *
 * @author Jelle Sebreghts
 */
class ArrayIntersections {

  /**
   * The array of arrays to find intersections for.
   *
   * @var array
   */
  protected $arrays;

  /**
   * The array keys of the master array.
   *
   * @var array
   */
  protected $arrayKeys;

  /**
   * The size of the master array.
   *
   * @var int
   */
  protected $arraysSize;

  /**
   * The intersections that were found so far.
   *
   * @var array
   */
  protected $intersections;

  /**
   * The masks that resulted in no intersections
   *
   * @var array
   */
  protected $noResultMasks = [];

  /**
   * The threshold. The minimum size of the intersections to search for.
   *
   * @var int
   */
  protected $threshold;

  /**
   * The maximum number of combinations to try for finding intersections.
   *
   * @var int
   */
  protected $maxNumberOfCombinations;

  /**
   * Creates an Intersections object.
   *
   * @param array $arrays
   *   The array of arrays to find intersections for.
   * @param int $threshold
   *   The threshold. The minimum size of the intersections to search for.
   * @param int $maxNumberOfCombinations
   *   The maximum number of combinations to try for finding intersections.
   */
  public function __construct($arrays, $threshold, $maxNumberOfCombinations = 1000000) {
    $this->arrays = $arrays;
    $this->threshold = $threshold;
    if (count($this->arrays) === 1 && count(reset($this->arrays)) > $this->threshold) {
      $this->intersections = $this->arrays;
    }
    // Fitler the arrays. Those smaller than the threshold will not create an
    // intersection bigger than the threshold.
    foreach ($this->arrays as $key => $array) {
      if (count($array) < $this->threshold) {
        unset($this->arrays[$key]);
      }
    }
    $this->arraysSize = count($this->arrays);
    $this->arrayKeys = array_keys($this->arrays);
    $this->maxNumberOfCombinations = $maxNumberOfCombinations;
  }

  /**
   * Get all intersections given the constructor parameters.
   *
   * @return array
   */
  public function getAll() {
    if (is_null($this->intersections)) {
      $this->intersections = [];
      if ($this->arraysSize >= 2) {
        $this->createIntersections();
      }
    }

    return $this->intersections;
  }

  /**
   * Get the largest intersection found given the constructor parameters.
   *
   * @return array
   */
  public function getLargest() {
    $this->getAll();
    return $this->intersections ? reset($this->intersections) : NULL;
  }

  /**
   * Set the maximum number of combinations to try for finding intersections.
   *
   * @param int $max
   */
  public function setMaxNumberOfCombinations($max) {
    $this->maxNumberOfCombinations = $max;
  }

  /**
   * Create all intersections given the constructor parameters.
   */
  protected function createIntersections() {
    $totalNumberOfCombinations = min(pow(2, $this->arraysSize), $this->maxNumberOfCombinations);
    $maskGenerator = new BitMaskGenerator($this->arraysSize, 2);
    $i = 0;
    $noresult = 0;
    while ($i < $totalNumberOfCombinations && $noresult < $totalNumberOfCombinations && $mask = $maskGenerator->getNextMask()) {
      if (!$this->isNoResultMask($mask)) {
        $i++;
        $this->generateIntersection($mask);
        continue;
      }
      $noresult++;
    }

    if (!is_null($this->intersections)) {
      uasort($this->intersections, function ($a, $b) {
        return count($b) - count($a);
      });
    }
  }

  /**
   * Try to determine if this mask will result in no intersections based on
   * previously generated masks.
   *
   * @param string $mask
   *   The mask to check.
   *
   * @return boolean
   *   TRUE if this mask would result in an empty intersection, FALSE if not or
   *   if not known.
   */
  protected function isNoResultMask($mask) {
    foreach ($this->noResultMasks as $noresultMask) {
      if ($mask === $noresultMask) {
        return TRUE;
      }
      if (($mask & $noresultMask) === $noresultMask) {
        $this->noResultMasks[] = $mask;
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Generate intersections based on a combination mask.
   *
   * @param string $combinationMask
   *   The combination mask to try.
   */
  protected function generateIntersection($combinationMask) {
    $combination = [];
    foreach (str_split($combinationMask) as $key => $indicator) {
      if ($indicator) {
        $combination[] = $this->arrays[$this->arrayKeys[$key]];
      }
    }
    $intersection = call_user_func_array('array_intersect_assoc', $combination);
    if (count($intersection) >= $this->threshold) {
      $this->intersections[] = $intersection;
      return;
    }
    $this->noResultMasks[] = $combinationMask;
  }

}
