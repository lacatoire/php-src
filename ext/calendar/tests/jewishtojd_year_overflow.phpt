--TEST--
jewishtojd(): a date that jdtojewish() cannot convert back is invalid
--EXTENSIONS--
calendar
--FILE--
<?php
// Last convertible date: 12/13/887605
var_dump(jewishtojd(12, 13, 887605));
var_dump(jdtojewish(jewishtojd(12, 13, 887605)));
var_dump(jewishtojd(12, 14, 887605));
var_dump(jewishtojd(13, 1, 887605));
var_dump(jewishtojd(1, 1, 887606));
var_dump(jewishtojd(1, 1, 10000000));
var_dump(jewishtojd(1, 1, 2147483645));
?>
--EXPECT--
int(324542846)
string(12) "12/13/887605"
int(0)
int(0)
int(0)
int(0)
int(0)
