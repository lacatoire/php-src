--TEST--
stream_socket_server(): a NUL byte in a unix path is rejected, an abstract name is still accepted
--SKIPIF--
<?php
if (!in_array('unix', stream_get_transports())) {
    die('skip unix sockets not available');
}
?>
--FILE--
<?php

$path = __DIR__ . '/stream_socket_server_unix_nul_path.sock';
@unlink($path);

var_dump(stream_socket_server("unix://$path\0suffix", $errno, $errstr));
var_dump($errstr);
var_dump(file_exists($path));
@unlink($path);

if (PHP_OS_FAMILY === 'Linux') {
    var_dump(is_resource(stream_socket_server("unix://\0php-nul-test-" . getmypid())));
} else {
    var_dump(true);
}

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_socket_server_unix_nul_path.sock');
?>
--EXPECTF--
Warning: stream_socket_server(): Unable to connect to unix://%s (The path must not contain null bytes) in %s on line %d
bool(false)
string(%d) "The path must not contain null bytes"
bool(false)
bool(true)
