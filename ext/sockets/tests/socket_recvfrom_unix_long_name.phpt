--TEST--
socket_recvfrom(): AF_UNIX sender bound to the longest name PHP can bind
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

$dir = __DIR__ . '/socket_recvfrom_unix_long_name';
mkdir($dir);
chdir($dir);

$name = str_repeat('a', 107);

$receiver = socket_create(AF_UNIX, SOCK_DGRAM, 0);
socket_bind($receiver, 'receiver.sock');

$sender = socket_create(AF_UNIX, SOCK_DGRAM, 0);
socket_bind($sender, $name);
socket_sendto($sender, 'x', 1, 0, 'receiver.sock');

var_dump(socket_recvfrom($receiver, $data, 10, MSG_DONTWAIT, $from));
var_dump($data);
var_dump(strlen($from));
var_dump($from === $name);

?>
--CLEAN--
<?php
$dir = __DIR__ . '/socket_recvfrom_unix_long_name';
@unlink($dir . '/receiver.sock');
@unlink($dir . '/' . str_repeat('a', 107));
@rmdir($dir);
?>
--EXPECT--
int(1)
string(1) "x"
int(107)
bool(true)
