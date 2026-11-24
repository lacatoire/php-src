--TEST--
lstat() of a symlink does not fill the stat() cache
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip no symlink on Windows without privileges');
}
?>
--FILE--
<?php
$file = __DIR__ . '/lstat_does_not_fill_stat_cache_target.txt';
$link = __DIR__ . '/lstat_does_not_fill_stat_cache_link';
touch($file);
symlink($file, $link);

$lstat = lstat($link);
$stat = stat($link);
var_dump(($lstat['mode'] & 0170000) === 0120000);
var_dump(($stat['mode'] & 0170000) === 0100000);

clearstatcache();
$stat = stat($link);
$lstat = lstat($link);
var_dump(($stat['mode'] & 0170000) === 0100000);
var_dump(($lstat['mode'] & 0170000) === 0120000);
var_dump(is_link($link), is_file($link));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/lstat_does_not_fill_stat_cache_link');
@unlink(__DIR__ . '/lstat_does_not_fill_stat_cache_target.txt');
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
