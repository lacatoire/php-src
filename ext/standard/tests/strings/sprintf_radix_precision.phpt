--TEST--
sprintf(): precision is ignored on %x, %X, %o and %b
--FILE--
<?php
var_dump(sprintf("%.3x", 255));
var_dump(sprintf("%5.3X", 255));
var_dump(sprintf("%.3o", 8));
var_dump(sprintf("%-6.2b|", 5));
?>
--EXPECT--
string(2) "ff"
string(5) "   FF"
string(2) "10"
string(7) "101   |"
