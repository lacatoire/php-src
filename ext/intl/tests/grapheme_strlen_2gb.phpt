--TEST--
grapheme_strlen() with an ASCII string of 2 GiB
--EXTENSIONS--
intl
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die('skip this test is for 64bit platforms only');
if (getenv("SKIP_SLOW_TESTS")) die('skip slow test');
if (getenv("SKIP_ASAN")) die('skip too much memory for asan');
ini_set('memory_limit', '-1');
if (@str_repeat('a', 2**31) === '') die('skip not enough memory');
?>
--INI--
memory_limit=-1
--FILE--
<?php
$s = str_repeat("a", 2**31);
var_dump(grapheme_strlen($s));
?>
--EXPECT--
int(2147483648)
