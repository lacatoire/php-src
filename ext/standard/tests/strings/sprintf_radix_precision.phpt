--TEST--
sprintf(): precision is ignored on %x, %X, %o and %b
--FILE--
<?php
var_dump(sprintf("%.3x", 255));
var_dump(sprintf("%5.3x", 255));
var_dump(sprintf("%.3X", 255));
var_dump(sprintf("%.3o", 8));
var_dump(sprintf("%.3b", 5));
var_dump(sprintf("%-6.2x|", 255));
?>
--EXPECT--
string(2) "ff"
string(5) "   ff"
string(2) "FF"
string(2) "10"
string(3) "101"
string(7) "ff    |"
