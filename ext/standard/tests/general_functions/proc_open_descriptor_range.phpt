--TEST--
proc_open() rejects descriptor keys and redirect targets outside the int range
--SKIPIF--
<?php
if (PHP_INT_SIZE != 8) die("skip 64-bit only");
?>
--FILE--
<?php

$cmd = [getenv('TEST_PHP_EXECUTABLE'), '-n', '-r', 'echo "hi";'];

foreach ([(1 << 32) + 1, -1] as $key) {
    try {
        proc_open($cmd, [$key => ['pipe', 'w']], $pipes);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

foreach ([(1 << 32) + 1, -1] as $target) {
    try {
        proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['redirect', $target]], $pipes);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

?>
--EXPECTF--
proc_open(): Argument #2 ($descriptor_spec) descriptor keys must be between 0 and %d
proc_open(): Argument #2 ($descriptor_spec) descriptor keys must be between 0 and %d
Redirection target must be between 0 and %d
Redirection target must be between 0 and %d
