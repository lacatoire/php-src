--TEST--
proc_nice() does not truncate its argument to int
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
if (PHP_INT_SIZE != 8) die('skip 64-bit only');
if (!extension_loaded('pcntl')) die('skip pcntl extension required');
if (!function_exists('proc_nice')) die('skip proc_nice() not available');
?>
--FILE--
<?php
// 2**32 + 3 used to wrap to nice(3); it must now behave like a huge increment.
var_dump(proc_nice((1 << 32) + 3));
var_dump(pcntl_getpriority() > 3);
var_dump(proc_nice(PHP_INT_MAX));
var_dump(pcntl_getpriority());
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
int(19)
