--TEST--
stream_copy_to_stream() file to a descriptor inherited with O_APPEND
--SKIPIF--
<?php
if (!function_exists('proc_open')) die('skip proc_open() is not available');
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--FILE--
<?php

$srcFile = __DIR__ . '/stream_copy_append_descriptor_src.txt';
$dstFile = __DIR__ . '/stream_copy_append_descriptor_dst.txt';

file_put_contents($srcFile, str_repeat("b", 3000));
file_put_contents($dstFile, "PREFIX-");

/* The child opens php://stdout in "w" mode: only the descriptor carries O_APPEND. */
$code = <<<'CODE'
$src = fopen($argv[1], 'r');
$dst = fopen('php://stdout', 'w');
fwrite(STDERR, var_export(stream_copy_to_stream($src, $dst), true));
CODE;

$process = proc_open(
    [getenv('TEST_PHP_EXECUTABLE'), '-n', '-r', $code, '--', $srcFile],
    [1 => ['file', $dstFile, 'a'], 2 => ['pipe', 'w']],
    $pipes
);
var_dump(stream_get_contents($pipes[2]));
fclose($pipes[2]);
proc_close($process);

$result = file_get_contents($dstFile);
var_dump(strlen($result));
var_dump($result === "PREFIX-" . str_repeat("b", 3000));
?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_copy_append_descriptor_src.txt');
@unlink(__DIR__ . '/stream_copy_append_descriptor_dst.txt');
?>
--EXPECT--
string(4) "3000"
int(3007)
bool(true)
