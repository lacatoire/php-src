--TEST--
opendir() and dir() with an empty path emit a warning
--FILE--
<?php
var_dump(opendir(""));
var_dump(dir(""));
var_dump(error_get_last()["message"]);
?>
--EXPECTF--
Warning: opendir(): Failed to open directory: Path must not be empty in %s on line %d
bool(false)

Warning: dir(): Failed to open directory: Path must not be empty in %s on line %d
bool(false)
string(%d) "dir(): Failed to open directory: Path must not be empty"
