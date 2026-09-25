--TEST--
array_reduce() / array_map() / array_filter() / array_find() / array_any() / array_all(): a by-ref callback parameter must not write through a refcount-1 leftover reference
--FILE--
<?php
// The classic "break the reference" idiom: after this, $a's elements are
// plain values again, not references, even though the internal zvals were
// briefly wrapped in a refcount-1 zend_reference during the foreach. A
// by-ref callback parameter must not silently write through that leftover
// wrapper: it must behave exactly like a plain array, with a warning and no
// mutation of the caller's array.
function mk() { $a = [1, 2]; foreach ($a as &$v) {} unset($v); return $a; }

$a = mk();
array_reduce($a, function ($c, &$i) { $i = 9; return $c; });
var_dump($a);

$a = mk();
array_map(function (&$i) { $i = 9; }, $a);
var_dump($a);

$a = mk();
array_map(function (&$i, $j) { $i = 9; }, $a, [7, 8]);
var_dump($a);

$a = mk();
array_filter($a, function (&$i) { $i = 9; return true; });
var_dump($a);

$a = mk();
array_find($a, function (&$i) { $i = 9; return false; });
var_dump($a);

$a = mk();
array_any($a, function (&$i) { $i = 9; return false; });
var_dump($a);

$a = mk();
array_all($a, function (&$i) { $i = 9; return true; });
var_dump($a);

// Control: a genuine shared reference (refcount > 1, an explicit &$var) must
// still be writable through the callback, unaffected by this fix.
$n = 1;
$b = [&$n, 2];
array_reduce($b, function ($c, &$i) { $i = 99; return $c; });
var_dump($n);
?>
--EXPECTF--
Warning: {closure%S}(): Argument #2 ($i) must be passed by reference, value given in %s on line %d

Warning: {closure%S}(): Argument #2 ($i) must be passed by reference, value given in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d

Warning: {closure%S}(): Argument #1 ($i) must be passed by reference, value given in %s on line %d
array(2) {
  [0]=>
  int(1)
  [1]=>
  int(2)
}

Warning: {closure%S}(): Argument #2 ($i) must be passed by reference, value given in %s on line %d
int(99)
