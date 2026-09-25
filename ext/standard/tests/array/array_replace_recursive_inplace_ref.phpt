--TEST--
array_replace_recursive(): does not assert/crash when the first argument is a temporary holding a refcount-1 reference to a sub-array
--FILE--
<?php
// After foreach ($a as &$v) {} unset($v);, $a's elements are still wrapped
// in a refcount-1 zend_reference. Calling array_replace_recursive()
// directly on such a temporary (not stored in a variable) takes the
// in-place optimization path, which does not zend_array_dup() the
// argument first.
function mk() {
    $a = ['x' => [1], 'y' => [2]];
    foreach ($a as &$v) {}
    unset($v);
    return $a;
}

$r = array_replace_recursive(mk(), ['x' => [9], 'y' => [8]]);
var_dump($r);

// Control: the same call via a named variable (copy path, not in-place)
// already worked before this fix and must keep working.
$a = mk();
$r = array_replace_recursive($a, ['x' => [9], 'y' => [8]]);
var_dump($r);

// A genuinely shared reference (refcount > 1) must still behave like a
// normal reference: array_replace_recursive() replaces its array value.
$shared = [1];
$a = ['x' => &$shared];
$r = array_replace_recursive($a, ['x' => [9]]);
var_dump($r);
var_dump($shared);
?>
--EXPECT--
array(2) {
  ["x"]=>
  array(1) {
    [0]=>
    int(9)
  }
  ["y"]=>
  array(1) {
    [0]=>
    int(8)
  }
}
array(2) {
  ["x"]=>
  array(1) {
    [0]=>
    int(9)
  }
  ["y"]=>
  array(1) {
    [0]=>
    int(8)
  }
}
array(1) {
  ["x"]=>
  array(1) {
    [0]=>
    int(9)
  }
}
array(1) {
  [0]=>
  int(1)
}
