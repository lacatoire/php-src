--TEST--
posix_getpgid(), posix_kill(), posix_strerror(), posix_sysconf(): out of range arguments are rejected
--EXTENSIONS--
posix
--SKIPIF--
<?php
if (PHP_INT_SIZE < 8) die("skip 64-bit only");
if (!function_exists('posix_getpgid')) die("skip posix_getpgid() not available");
?>
--FILE--
<?php
$wrap = 1 << 32;

foreach ([
    'posix_getpgid' => fn() => posix_getpgid(getmypid() + $wrap),
    'posix_getpgid max' => fn() => posix_getpgid(PHP_INT_MAX),
    'posix_kill' => fn() => posix_kill(getmypid(), $wrap),
    'posix_strerror' => fn() => posix_strerror($wrap + 2),
    'posix_sysconf' => fn() => posix_sysconf($wrap + 30),
] as $name => $f) {
    try {
        var_dump($f());
    } catch (ValueError $e) {
        echo $e->getMessage(), PHP_EOL;
    }
}
?>
--EXPECT--
posix_getpgid(): Argument #1 ($process_id) must be between -2147483648 and 2147483647
posix_getpgid(): Argument #1 ($process_id) must be between -2147483648 and 2147483647
posix_kill(): Argument #2 ($signal) must be between -2147483648 and 2147483647
posix_strerror(): Argument #1 ($error_code) must be between -2147483648 and 2147483647
posix_sysconf(): Argument #1 ($conf_id) must be between -2147483648 and 2147483647
