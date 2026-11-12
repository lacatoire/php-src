--TEST--
socket_recvmsg(): a failure is recorded on the Socket and EAGAIN does not warn
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY === 'Windows') {
    die('skip not for Windows');
}
?>
--FILE--
<?php

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, '127.0.0.1', 0);
socket_set_nonblock($socket);

$message = ['name' => [], 'buffer_size' => 64, 'controllen' => socket_cmsg_space(SOL_SOCKET, SCM_RIGHTS, 1)];
var_dump(socket_recvmsg($socket, $message, 0));
var_dump(socket_last_error() === SOCKET_EAGAIN);
var_dump(socket_last_error($socket) === SOCKET_EAGAIN);

$tcp = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
$message = ['buffer_size' => 64, 'controllen' => socket_cmsg_space(SOL_SOCKET, SCM_RIGHTS, 1)];
var_dump(socket_recvmsg($tcp, $message, 0));
var_dump(socket_last_error($tcp) === SOCKET_ENOTCONN);

?>
--EXPECTF--
bool(false)
bool(true)
bool(true)

Warning: socket_recvmsg(): Error in recvmsg [%d]: %s in %s on line %d
bool(false)
bool(true)
