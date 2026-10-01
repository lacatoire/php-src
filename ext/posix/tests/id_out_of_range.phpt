--TEST--
posix_*() functions reject ids and modes that do not fit in uid_t/gid_t/mode_t
--SKIPIF--
<?php
if (!extension_loaded('posix')) die('skip posix extension not available');
if (PHP_INT_SIZE != 8) die('skip 64-bit only');
if (!function_exists('posix_mkfifo')) die('skip posix_mkfifo not available');
if (!function_exists('posix_seteuid') || !function_exists('posix_setegid')) die('skip posix_seteuid/posix_setegid not available');
?>
--FILE--
<?php

$tooLarge = 1 << 32;

$calls = [
    'posix_setuid' => fn() => posix_setuid($tooLarge + posix_getuid()),
    'posix_setgid' => fn() => posix_setgid($tooLarge + posix_getgid()),
    'posix_seteuid' => fn() => posix_seteuid($tooLarge + posix_geteuid()),
    'posix_setegid' => fn() => posix_setegid($tooLarge + posix_getegid()),
    'posix_getpwuid' => fn() => posix_getpwuid($tooLarge),
    'posix_getgrgid' => fn() => posix_getgrgid($tooLarge),
    'posix_mkfifo' => fn() => posix_mkfifo(__DIR__ . '/id_out_of_range_fifo', $tooLarge + 0600),
];

foreach ($calls as $name => $call) {
    try {
        var_dump($call());
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

var_dump(file_exists(__DIR__ . '/id_out_of_range_fifo'));

?>
--EXPECT--
posix_setuid(): Argument #1 ($user_id) is too large
posix_setgid(): Argument #1 ($group_id) is too large
posix_seteuid(): Argument #1 ($user_id) is too large
posix_setegid(): Argument #1 ($group_id) is too large
posix_getpwuid(): Argument #1 ($user_id) is too large
posix_getgrgid(): Argument #1 ($group_id) is too large
posix_mkfifo(): Argument #2 ($permissions) is too large
bool(false)
--CLEAN--
<?php
@unlink(__DIR__ . '/id_out_of_range_fifo');
?>
