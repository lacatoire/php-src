--TEST--
version_compare() with a long "#"-prefixed version uses linear memory
--INI--
memory_limit=32M
--FILE--
<?php
$version = '#' . str_repeat('.#', 12000);
var_dump(version_compare($version, '1.0'));
var_dump(version_compare('1.0', $version));
var_dump(version_compare($version, $version . '.#'));
?>
--EXPECT--
int(0)
int(0)
int(0)
