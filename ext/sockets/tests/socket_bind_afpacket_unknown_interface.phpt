--TEST--
socket_bind(): AF_PACKET with an interface name that does not exist
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

$socket = socket_create(AF_PACKET, SOCK_RAW, 3);
var_dump(socket_bind($socket, 'nosuchif0'));
var_dump(socket_bind($socket, ''));

?>
--EXPECTF--
Warning: socket_bind(): No interface with name "nosuchif0" could be found in %s on line %d
bool(false)

Warning: socket_bind(): No interface with name "" could be found in %s on line %d
bool(false)
