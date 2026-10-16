--TEST--
gzcompress() with an input of 4 GiB or more
--EXTENSIONS--
zlib
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die('skip this test is for 64bit platforms only');
if (getenv("SKIP_SLOW_TESTS")) die('skip slow test');
if (getenv("SKIP_ASAN")) die('skip too much memory for asan');
ini_set('memory_limit', '-1');
if (@str_repeat('a', 2**32 + 100) === '') die('skip not enough memory');
?>
--INI--
memory_limit=-1
--FILE--
<?php
$s = str_repeat("a", 2**32 + 100);
$c = gzcompress($s, 1);
// The zlib stream ends with the Adler-32 checksum of everything that was compressed
var_dump(bin2hex(substr($c, -4)) === hash('adler32', $s));
?>
--EXPECT--
bool(true)
