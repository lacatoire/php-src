--TEST--
lcfirst() does not copy a string whose first byte is not an uppercase ASCII letter
--FILE--
<?php
$string = "\xe9" . str_repeat('a', 1 << 20);

$before = memory_get_usage();
$result = lcfirst($string);
var_dump(memory_get_usage() - $before < 1 << 19);
var_dump($result === $string);
?>
--EXPECT--
bool(true)
bool(true)
