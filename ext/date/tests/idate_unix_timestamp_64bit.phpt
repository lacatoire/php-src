--TEST--
idate(): 'U' and 'Y' return the full 64-bit value
--FILE--
<?php
date_default_timezone_set('UTC');
var_dump(idate('U', 2147483648));
var_dump(idate('U', 4294967301));
var_dump(idate('U', -2147483649));
var_dump(idate('U', PHP_INT_MAX));
var_dump(idate('U', 0));
var_dump(idate('Y', mktime(0, 0, 0, 1, 1, -1)));
var_dump(idate('Y', PHP_INT_MAX));
?>
--EXPECT--
int(2147483648)
int(4294967301)
int(-2147483649)
int(9223372036854775807)
int(0)
int(-1)
int(292277026596)
