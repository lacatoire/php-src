--TEST--
stream_socket_server(): $error_code and $error_message are set for bind and listen failures
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') {
    die('skip error numbers are Linux specific');
}
?>
--FILE--
<?php

$first = stream_socket_server('tcp://127.0.0.1:0');
$address = stream_socket_get_name($first, false);
var_dump(@stream_socket_server("tcp://$address", $errno, $errstr), $errno, $errstr);

$path = __DIR__ . '/stream_socket_server_error_code.sock';
@unlink($path);
$unix = stream_socket_server("unix://$path");
var_dump(@stream_socket_server("unix://$path", $errno, $errstr), $errno, $errstr);

var_dump(@stream_socket_server('udp://127.0.0.1:0', $errno, $errstr), $errno, $errstr);

?>
--CLEAN--
<?php
@unlink(__DIR__ . '/stream_socket_server_error_code.sock');
?>
--EXPECT--
bool(false)
int(98)
string(22) "Address already in use"
bool(false)
int(98)
string(22) "Address already in use"
bool(false)
int(95)
string(23) "Operation not supported"
