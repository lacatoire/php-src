--TEST--
sprintf(): zero padding is not applied on the right for %x, %X, %o and %b
--FILE--
<?php
var_dump(sprintf("%-05d", 1));
var_dump(sprintf("%-05u", 3));
var_dump(sprintf("%-05x", 1));
var_dump(sprintf("%-05X", 10));
var_dump(sprintf("%-05o", 8));
var_dump(sprintf("%-05b", 5));
var_dump(sprintf("%05x", 1));
var_dump(sprintf("%-5x", 1));
?>
--EXPECT--
string(5) "1    "
string(5) "3    "
string(5) "1    "
string(5) "A    "
string(5) "10   "
string(5) "101  "
string(5) "00001"
string(5) "1    "
