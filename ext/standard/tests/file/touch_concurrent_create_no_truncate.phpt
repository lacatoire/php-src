--TEST--
touch() does not truncate a file created concurrently by another process
--SKIPIF--
<?php
if (!function_exists('proc_open')) die('skip proc_open() not available');
if (getenv('SKIP_SLOW_TESTS')) die('skip slow test');
?>
--FILE--
<?php
$file = __DIR__ . '/touch_concurrent_create_no_truncate.txt';
$stop = __DIR__ . '/touch_concurrent_create_no_truncate.stop';
@unlink($stop);

$toucher = proc_open(
    [PHP_BINARY, '-n', '-r', 'while (!file_exists($argv[2])) { @touch($argv[1]); }', $file, $stop],
    [],
    $pipes
);

$truncated = 0;
for ($i = 0; $i < 5000; $i++) {
    @unlink($file);
    file_put_contents("$file.tmp", str_repeat('x', 4096));
    rename("$file.tmp", $file);
    usleep(20);
    clearstatcache();
    $truncated += filesize($file) !== 4096;
}

touch($stop);
proc_close($toucher);
var_dump($truncated);
?>
--CLEAN--
<?php
$file = __DIR__ . '/touch_concurrent_create_no_truncate.txt';
@unlink($file);
@unlink("$file.tmp");
@unlink(__DIR__ . '/touch_concurrent_create_no_truncate.stop');
?>
--EXPECT--
int(0)
