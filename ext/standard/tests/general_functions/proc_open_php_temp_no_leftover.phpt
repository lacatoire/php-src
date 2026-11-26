--TEST--
proc_open() with a php://temp descriptor does not leave a temporary file
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--INI--
sys_temp_dir={PWD}
--FILE--
<?php
$before = glob(__DIR__ . '/php*');
$process = proc_open('echo hi', [1 => ['file', 'php://temp', 'w']], $pipes);
var_dump(proc_close($process));
var_dump(array_values(array_diff(glob(__DIR__ . '/php*'), $before)));
?>
--EXPECT--
int(0)
array(0) {
}
