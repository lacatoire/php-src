--TEST--
unpack() with "@0" rewinds to the start of the data
--FILE--
<?php
var_dump(unpack('Ca/Cb/@0/Cc', "abc"));
var_dump(unpack('Ca/Cb/@1/Cc', "abc"));
?>
--EXPECT--
array(3) {
  ["a"]=>
  int(97)
  ["b"]=>
  int(98)
  ["c"]=>
  int(97)
}
array(3) {
  ["a"]=>
  int(97)
  ["b"]=>
  int(98)
  ["c"]=>
  int(98)
}
