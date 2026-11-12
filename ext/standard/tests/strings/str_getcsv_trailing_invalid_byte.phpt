--TEST--
str_getcsv() keeps the bytes that follow a line ending when they are not a valid character
--FILE--
<?php
var_dump(str_getcsv("a,b\n\xff", ',', '"', '\\'));
var_dump(str_getcsv("a,b\r\xe9", ',', '"', '\\'));
var_dump(str_getcsv("a,b\n", ',', '"', '\\'));
?>
--EXPECT--
array(2) {
  [0]=>
  string(1) "a"
  [1]=>
  string(3) "b
ÿ"
}
array(2) {
  [0]=>
  string(1) "a"
  [1]=>
  string(3) "bé"
}
array(2) {
  [0]=>
  string(1) "a"
  [1]=>
  string(1) "b"
}
