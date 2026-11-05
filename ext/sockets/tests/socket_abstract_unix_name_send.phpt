--TEST--
socket_sendto() and socket_sendmsg(): abstract AF_UNIX names
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') {
    die('skip abstract unix sockets are Linux only');
}
?>
--FILE--
<?php

$id = getmypid();
$prefix = socket_create(AF_UNIX, SOCK_DGRAM, 0);
socket_bind($prefix, "\0ab$id");
$full = socket_create(AF_UNIX, SOCK_DGRAM, 0);
socket_bind($full, "\0ab$id\0cd");
$sender = socket_create(AF_UNIX, SOCK_DGRAM, 0);

echo "socket_sendto()\n";
var_dump(socket_sendto($sender, 'one', 3, 0, "\0ab$id"));
var_dump(socket_sendto($sender, 'two', 3, 0, "\0ab$id\0cd"));
var_dump(socket_recv($prefix, $data, 8, MSG_DONTWAIT), $data);
var_dump(socket_recv($full, $data, 8, MSG_DONTWAIT), $data);
var_dump(socket_recv($prefix, $data, 8, MSG_DONTWAIT));

echo "socket_sendmsg()\n";
var_dump(socket_sendmsg($sender, ['name' => ['path' => "\0ab$id\0cd"], 'iov' => ['three']], 0));
var_dump(socket_recv($prefix, $data, 8, MSG_DONTWAIT));
var_dump(socket_recv($full, $data, 8, MSG_DONTWAIT), $data);

?>
--EXPECT--
socket_sendto()
int(3)
int(3)
int(3)
string(3) "one"
int(3)
string(3) "two"
bool(false)
socket_sendmsg()
int(5)
bool(false)
int(5)
string(5) "three"
