--TEST--
socket_accept(): a failed accept stores the error on the listening socket
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (strtolower(substr(PHP_OS, 0, 3)) == 'win') die('skip not for Windows');
?>
--FILE--
<?php
$l = socket_create(AF_INET, SOCK_STREAM, 0);
socket_bind($l, '127.0.0.1', 0);
socket_listen($l);
socket_set_nonblock($l);
var_dump(socket_accept($l));
var_dump(socket_last_error($l) === SOCKET_EAGAIN, socket_last_error() === SOCKET_EAGAIN);
socket_clear_error($l);
var_dump(socket_last_error($l));

$u = socket_create(AF_INET, SOCK_STREAM, 0);
var_dump(@socket_accept($u));
var_dump(socket_last_error($u) === SOCKET_EINVAL);
?>
--EXPECT--
bool(false)
bool(true)
bool(true)
int(0)
bool(false)
bool(true)
