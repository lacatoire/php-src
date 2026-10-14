--TEST--
array_replace_recursive(): a destructor freeing the iterated source array must not cause a use-after-free
--FILE--
<?php
// Overwriting a destination value can run that value's destructor (dest may
// be modified in place). If the destructor drops the last other reference
// to the source array we are still iterating (reachable through a shared
// &$var), the source used to be freed out from under the FOREACH loop.
class D {
    function __destruct() {
        $GLOBALS['x'] = null;
    }
}

$x = [];
foreach (['a', 'b', 'c', 'd', 'e', 'f', 'g'] as $i => $k) {
    $x[$k] = $i + 1;
}
$src = ['k' => &$x];
$r = array_replace_recursive(['k' => ['a' => new D]], $src);
var_dump($r);

// Control: the same shape, but the first argument is stored in a variable
// before the call rather than passed as a bare temporary; must keep
// working the same way.
class D2 {
    function __destruct() {
        $GLOBALS['y'] = null;
    }
}
$y = [];
foreach (['a', 'b', 'c'] as $i => $k) {
    $y[$k] = $i + 1;
}
$src2 = ['k' => &$y];
$dest2 = ['k' => ['a' => new D2]];
$r2 = array_replace_recursive($dest2, $src2);
var_dump($r2);

// Empty source array (possibly the shared immutable empty-array singleton)
// must not trip the protective refcounting added for this fix.
var_dump(array_replace_recursive([0, 1], []));
var_dump(array_replace_recursive(['a' => ['x' => 1]], []));

echo "done, no crash\n";
?>
--EXPECT--
array(1) {
  ["k"]=>
  array(7) {
    ["a"]=>
    int(1)
    ["b"]=>
    int(2)
    ["c"]=>
    int(3)
    ["d"]=>
    int(4)
    ["e"]=>
    int(5)
    ["f"]=>
    int(6)
    ["g"]=>
    int(7)
  }
}
array(1) {
  ["k"]=>
  array(3) {
    ["a"]=>
    int(1)
    ["b"]=>
    int(2)
    ["c"]=>
    int(3)
  }
}
array(2) {
  [0]=>
  int(0)
  [1]=>
  int(1)
}
array(1) {
  ["a"]=>
  array(1) {
    ["x"]=>
    int(1)
  }
}
done, no crash
