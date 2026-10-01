--TEST--
preg_split() treats every negative limit as "no limit"
--FILE--
<?php
var_dump(preg_split('/,/', 'a,b,c,d', -1));
var_dump(preg_split('/,/', 'a,b,c,d', -2));
var_dump(preg_split('/,/', 'a,b,c,d', PHP_INT_MIN));
var_dump(preg_split('//', 'abc', -5, PREG_SPLIT_NO_EMPTY));
var_dump(preg_replace('/,/', ';', 'a,b,c,d', -2));
?>
--EXPECT--
array(4) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
  [3]=>
  string(1) "d"
}
array(4) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
  [3]=>
  string(1) "d"
}
array(4) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
  [3]=>
  string(1) "d"
}
array(3) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
  [2]=>
  string(1) "c"
}
string(7) "a;b;c;d"
