--TEST--
mb_ereg(): empty match and empty groups are "", unmatched groups are false
--EXTENSIONS--
mbstring
--SKIPIF--
<?php
if (!function_exists('mb_ereg')) die('skip mb_ereg() not available');
?>
--FILE--
<?php
mb_ereg("(a*)b", "b", $m);
var_dump($m);
mb_ereg("a*", "b", $m);
var_dump($m);
mb_ereg("(x)?y", "y", $m);
var_dump($m);
mb_ereg("(?<n>a*)b", "b", $m);
var_dump($m);
?>
--EXPECTF--
Deprecated: Function mb_ereg() is deprecated since %s in %s on line %d
array(2) {
  [0]=>
  string(1) "b"
  [1]=>
  string(0) ""
}

Deprecated: Function mb_ereg() is deprecated since %s in %s on line %d
array(1) {
  [0]=>
  string(0) ""
}

Deprecated: Function mb_ereg() is deprecated since %s in %s on line %d
array(2) {
  [0]=>
  string(1) "y"
  [1]=>
  bool(false)
}

Deprecated: Function mb_ereg() is deprecated since %s in %s on line %d
array(3) {
  [0]=>
  string(1) "b"
  [1]=>
  string(0) ""
  ["n"]=>
  string(0) ""
}
