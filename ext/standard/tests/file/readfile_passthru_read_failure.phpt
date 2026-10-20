--TEST--
readfile(), readgzfile() and fpassthru() when the first read fails
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip Reading a directory does not fail the same way on Windows');
if (!extension_loaded('zlib')) die('skip zlib extension not available');
?>
--FILE--
<?php
$dir = __DIR__;
var_dump(@readfile($dir));
var_dump(@readgzfile($dir));
var_dump(@fpassthru(fopen($dir, 'r')));
?>
--EXPECT--
bool(false)
bool(false)
int(0)
