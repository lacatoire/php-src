--TEST--
stream_socket_server(): the error text is not "Success" when socket() fails for a tcp address
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip not for Windows');
}
if (!function_exists('proc_open') || !is_executable('/bin/sh')) {
    die('skip proc_open or /bin/sh not available');
}
?>
--FILE--
<?php

$script = <<<'SCRIPT'
<?php
$keep = [];
while (($file = @fopen(__FILE__, 'r')) !== false) {
    $keep[] = $file;
}
var_dump(stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr));
var_dump($errstr !== 'Success');
SCRIPT;

$file = __DIR__ . '/stream_socket_server_socket_failure_error.inc.php';
file_put_contents($file, $script);
$php = escapeshellarg(PHP_BINARY);
echo shell_exec("ulimit -n 32; $php -n " . escapeshellarg($file) . ' 2>&1');

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_socket_server_socket_failure_error.inc.php');
?>
--EXPECTF--
Warning: stream_socket_server(): Unable to connect to tcp://127.0.0.1:0 (%s) in %s on line %d
bool(false)
bool(true)
