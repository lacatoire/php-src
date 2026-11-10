--TEST--
mb_convert_encoding() with an array that refers to itself twice
--EXTENSIONS--
mbstring
--FILE--
<?php

$a = [];
$a[0] = &$a;
var_dump(mb_convert_encoding($a, 'UTF-8', 'ISO-8859-1'));

$b = [];
$b[0] = &$b;
$b[1] = &$b;
var_dump(count(mb_convert_encoding($b, 'UTF-8', 'ISO-8859-1')));

?>
--EXPECTF--
Warning: mb_convert_encoding(): Cannot convert recursively referenced values in %s on line %d
array(1) {
  [0]=>
  array(0) {
  }
}

Warning: mb_convert_encoding(): Cannot convert recursively referenced values in %s on line %d

Warning: mb_convert_encoding(): Cannot convert recursively referenced values in %s on line %d
int(2)
