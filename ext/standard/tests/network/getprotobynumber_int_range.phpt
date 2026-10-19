--TEST--
getprotobynumber() rejects a $protocol outside the int range instead of aliasing it
--SKIPIF--
<?php
if (!extension_loaded('standard')) die('skip standard extension not available');
if (getenv('SKIP_MSAN')) die('skip msan missing interceptor for getprotobynumber()');
?>
--FILE--
<?php
try {
    getprotobynumber(4294967296); // 2**32, wraps to 0 ("ip") when cast to a 32-bit int
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}

try {
    getprotobynumber(PHP_INT_MAX);
} catch (Throwable $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
?>
--EXPECTF--
ValueError: getprotobynumber(): Argument #1 ($protocol) must be between %i and %i
ValueError: getprotobynumber(): Argument #1 ($protocol) must be between %i and %i
