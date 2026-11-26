--TEST--
unixtojd() returns false for a timestamp whose year is out of range
--EXTENSIONS--
calendar
--SKIPIF--
<?php
if (PHP_INT_SIZE !== 8) die('skip 64-bit only');
?>
--ENV--
TZ=UTC
--FILE--
<?php
var_dump(unixtojd(67767824760163199));
var_dump(unixtojd(67767824760163200));
var_dump(unixtojd(67768036191676800));
?>
--EXPECT--
int(784352264200)
bool(false)
bool(false)
