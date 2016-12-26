# ArrayIntersections
Tries to find intersections between a combination of arrays.

```php
use Jelle_S\Util\Intersections\ArrayIntersections;

// Get all possible intersections, where the minimum array size of an
// intersection is 3.
$arrays = array(
 array(
   'a' => 1,
   'b' => 2,
   'c' => 3,
   'd' => 4,
   'e' => 9,
 ),
 array(
   'a' => 1,
   'b' => 2,
   'c' => 3,
   'e' => 9,
 ),
 array(
   'a' => 1,
   'b' => 42,
   'c' => 3,
   'd' => 4,

 ),
 array(
   'b' => 42,
   'c' => 3,
   'a' => 1,
 ),
 array(
   'z' => 26,
   'e' => 9,
   'a' => 1,
 ),
);
$intersections = new Jelle_S\Util\Intersections\ArrayIntersections($arrays, 3);
print_r($intersections->getAll());

print_r($intersections->getLargest());
```

Output:
```
Array
(
    [2] => Array
        (
            [a] => 1
            [b] => 2
            [c] => 3
            [e] => 9
        )

    [1] => Array
        (
            [a] => 1
            [c] => 3
            [d] => 4
        )

    [0] => Array
        (
            [a] => 1
            [b] => 42
            [c] => 3
        )

)
Array
(
    [a] => 1
    [b] => 2
    [c] => 3
    [e] => 9
)
```
