--TEST--
sys_get_temp_dir() keeps "/" when TMPDIR is "/"
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not valid for Windows');
?>
--ENV--
TMPDIR=/
--FILE--
<?php
var_dump(sys_get_temp_dir());
?>
--EXPECT--
string(1) "/"
