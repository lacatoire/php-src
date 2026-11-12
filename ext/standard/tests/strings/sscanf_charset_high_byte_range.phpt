--TEST--
sscanf() character set ranges that cross 0x7f and 0x80
--FILE--
<?php
var_dump(sscanf("\xe9", "%[a-\xff]") === ["\xe9"]);
var_dump(sscanf("\x01", "%[a-\xff]") === [null]);
var_dump(sscanf("\xe9", "%[\xff-a]") === ["\xe9"]);
var_dump(sscanf("\xe9", "%[^a-\xff]") === [null]);
var_dump(sscanf("\xff\x80", "%[\x80\xff]") === ["\xff\x80"]);
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
