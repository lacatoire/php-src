--TEST--
socket_bind(): a failed AF_INET6 host lookup reports a known host lookup error
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') die('skip not for Windows');
?>
--FILE--
<?php

$socket = socket_create(AF_INET6, SOCK_STREAM, SOL_TCP);
var_dump(@socket_bind($socket, 'nosuchhost.invalid'));
var_dump(socket_last_error($socket) < -10000);
var_dump(str_starts_with(socket_strerror(socket_last_error($socket)), 'Unknown error'));

?>
--EXPECT--
bool(false)
bool(true)
bool(false)
