--TEST--
stream_socket_client() reports the error when socket() fails
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
if (!function_exists('proc_open')) die('skip no proc_open');
?>
--FILE--
<?php
// Run in a child process with a low file descriptor limit
$code = <<<'CODE'
$files = [];
while (($file = @fopen('/dev/null', 'r')) !== false) {
    $files[] = $file;
}
var_dump(@stream_socket_client('tcp://127.0.0.1:9', $errno, $errstr, 1));
var_dump($errno !== 0, $errstr !== '');
CODE;

$command = 'ulimit -n 40; ' . escapeshellarg(PHP_BINARY) . ' -n -r ' . escapeshellarg($code);
echo shell_exec($command);
?>
--EXPECT--
bool(false)
bool(true)
bool(true)
