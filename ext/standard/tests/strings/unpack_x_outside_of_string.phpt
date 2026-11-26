--TEST--
unpack() warns when "X" backs up past the start of the data
--FILE--
<?php
var_dump(unpack('Ca/X2/Cb', "ab"));
var_dump(unpack('Ca/X/Cb', "ab"));
?>
--EXPECTF--
Warning: unpack(): Type X: outside of string in %s on line %d
array(2) {
  ["a"]=>
  int(97)
  ["b"]=>
  int(97)
}
array(2) {
  ["a"]=>
  int(97)
  ["b"]=>
  int(97)
}
