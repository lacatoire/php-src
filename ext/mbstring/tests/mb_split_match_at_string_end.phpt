--TEST--
mb_split() zero-width match at the end of the string
--EXTENSIONS--
mbstring
--SKIPIF--
<?php
function_exists('mb_split') or die("skip mb_split() is not available in this build");
?>
--FILE--
<?php
var_dump(mb_split('$', "abc"));
var_dump(mb_split('\b', "ab"));
var_dump(mb_split('(?=$)', "abc"));
?>
--EXPECTF--
Deprecated: Function mb_split() is deprecated since 8.6, because the underlying library is no longer maintained in %s on line %d
array(2) {
  [0]=>
  string(3) "abc"
  [1]=>
  string(0) ""
}

Deprecated: Function mb_split() is deprecated since 8.6, because the underlying library is no longer maintained in %s on line %d
array(2) {
  [0]=>
  string(2) "ab"
  [1]=>
  string(0) ""
}

Deprecated: Function mb_split() is deprecated since 8.6, because the underlying library is no longer maintained in %s on line %d
array(2) {
  [0]=>
  string(3) "abc"
  [1]=>
  string(0) ""
}
