--TEST--
socket_getsockname() on an AF_PACKET socket not bound to an interface
--EXTENSIONS--
sockets
posix
--SKIPIF--
<?php

if (!defined("AF_PACKET")) {
    die('SKIP AF_PACKET not supported on this platform.');
}
if (!function_exists("posix_getuid") || posix_getuid() != 0) {
    die('SKIP AF_PACKET requires root permissions.');
}
?>
--FILE--
<?php

$socket = socket_create(AF_PACKET, SOCK_RAW, ETH_P_IP);

var_dump(socket_getsockname($socket, $name, $index));
var_dump($name);
var_dump($index);
var_dump(socket_last_error($socket));

socket_bind($socket, 'lo');
var_dump(socket_getsockname($socket, $name, $index));
var_dump($name);
var_dump($index > 0);

?>
--EXPECT--
bool(true)
string(0) ""
int(0)
int(0)
bool(true)
string(2) "lo"
bool(true)
