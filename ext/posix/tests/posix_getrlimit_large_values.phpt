--TEST--
posix_getrlimit() does not return negative values for limits above PHP_INT_MAX
--EXTENSIONS--
posix
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') die('skip Linux only');
if (!function_exists('posix_setrlimit') || !function_exists('posix_getrlimit')) die('skip no rlimit support');
if (PHP_INT_SIZE !== 8) die('skip 64-bit only');
if (!is_executable('/usr/bin/prlimit') && !is_executable('/bin/prlimit')) die('skip prlimit not available');
?>
--FILE--
<?php
$php = escapeshellarg(PHP_BINARY);
$code = escapeshellarg('var_dump(posix_getrlimit(POSIX_RLIMIT_CORE)); var_dump(posix_getrlimit()["soft core"]);');
passthru("prlimit --core=9223372036854775808:9223372036854775809 $php -n -r $code");

var_dump(posix_getrlimit((1 << 32) | POSIX_RLIMIT_CORE));
var_dump(posix_setrlimit((1 << 32) | POSIX_RLIMIT_CORE, 0, 0));
?>
--EXPECT--
array(2) {
  [0]=>
  int(9223372036854775807)
  [1]=>
  int(9223372036854775807)
}
int(9223372036854775807)
bool(false)
bool(false)
