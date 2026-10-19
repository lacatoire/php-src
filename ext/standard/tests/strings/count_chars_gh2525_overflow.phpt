--TEST--
GH-2525: count_chars() must not overflow its per-byte counter past INT_MAX occurrences
--SKIPIF--
<?php
if (!getenv('RUN_RESOURCE_HEAVY_TESTS')) die('skip resource-heavy test');
if (getenv('SKIP_SLOW_TESTS')) die('skip slow test');
if (PHP_INT_SIZE !== 8) die('skip Only for 64-bit systems');
$memInfo = @file_get_contents('/proc/meminfo');
if ($memInfo && preg_match('/MemAvailable:\s+(\d+) kB/', $memInfo, $m) && $m[1] < 3 * 1024 * 1024) {
    die('skip Insufficient available memory (less than 3 GiB)');
}
?>
--INI--
memory_limit=-1
--FILE--
<?php
// count_chars() used a 32-bit int frequency table, so a byte occurring more
// than INT_MAX times overflowed and reported a negative count.
$s = str_repeat("A", 2147483648); // 2^31
$r = count_chars($s, 1);
var_dump($r[65]);
?>
--EXPECT--
int(2147483648)
