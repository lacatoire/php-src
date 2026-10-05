--TEST--
mktime()/gmmktime(): integer overflow returns false
--SKIPIF--
<?php if (PHP_INT_SIZE != 8) die("skip 64-bit only"); ?>
--FILE--
<?php
date_default_timezone_set("UTC");
var_dump(mktime(0, 0, 0, 1, 1, 292277026596));
var_dump(mktime(0, 0, 0, 1, 1, 292277026597));
var_dump(mktime(0, 0, 0, 1, 1, -292277022000));
var_dump(mktime(0, 0, 0, 1, 1, -292277026597));
var_dump(gmmktime(0, 0, PHP_INT_MAX));
var_dump(gmmktime(PHP_INT_MAX));
var_dump(gmmktime(PHP_INT_MIN));
var_dump(mktime(PHP_INT_MAX));
var_dump(mktime(0, 0, 0, PHP_INT_MAX));
var_dump(mktime(0, 0, 0, 1, PHP_INT_MAX));
var_dump(gmmktime(12, 0, 0, 6, 15, 2020));
?>
--EXPECTF--
int(9223372036825516800)

Warning: mktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)
int(-9223372016124163200)

Warning: mktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)

Warning: gmmktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)

Warning: gmmktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)

Warning: gmmktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)

Warning: mktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)

Warning: mktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)

Warning: mktime(): Epoch doesn't fit in a PHP integer in %s on line %d
bool(false)
int(1592222400)
