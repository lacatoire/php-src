--TEST--
posix_pathconf() and posix_fpathconf() ignore a stale errno when there is no limit
--EXTENSIONS--
posix
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') die('skip Linux only');
if (posix_pathconf("/", POSIX_PC_SYMLINK_MAX) !== -1) die('skip SYMLINK_MAX has a limit here');
?>
--FILE--
<?php
@stat("/nonexistent");
var_dump(posix_pathconf("/", POSIX_PC_SYMLINK_MAX));
$fp = fopen(__FILE__, 'r');
@stat("/nonexistent");
var_dump(posix_fpathconf($fp, POSIX_PC_SYMLINK_MAX));
?>
--EXPECT--
int(-1)
int(-1)
