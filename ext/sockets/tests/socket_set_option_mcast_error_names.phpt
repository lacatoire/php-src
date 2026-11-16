--TEST--
socket_set_option(): the TypeError of the MCAST_* options names the option given
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS == 'Darwin') die('skip Not for macOS');
?>
--FILE--
<?php

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

foreach (['MCAST_JOIN_GROUP', 'MCAST_LEAVE_GROUP', 'MCAST_BLOCK_SOURCE', 'MCAST_UNBLOCK_SOURCE', 'MCAST_JOIN_SOURCE_GROUP', 'MCAST_LEAVE_SOURCE_GROUP'] as $name) {
    if (!defined($name)) {
        continue;
    }
    try {
        socket_set_option($socket, IPPROTO_IP, constant($name), 1);
    } catch (TypeError $e) {
        echo $e->getMessage(), "\n";
    }
}

?>
--EXPECT--
socket_set_option(): Argument #4 ($value) must be of type array when argument #3 ($option) is MCAST_JOIN_GROUP, int given
socket_set_option(): Argument #4 ($value) must be of type array when argument #3 ($option) is MCAST_LEAVE_GROUP, int given
socket_set_option(): Argument #4 ($value) must be of type array when argument #3 ($option) is MCAST_BLOCK_SOURCE, int given
socket_set_option(): Argument #4 ($value) must be of type array when argument #3 ($option) is MCAST_UNBLOCK_SOURCE, int given
socket_set_option(): Argument #4 ($value) must be of type array when argument #3 ($option) is MCAST_JOIN_SOURCE_GROUP, int given
socket_set_option(): Argument #4 ($value) must be of type array when argument #3 ($option) is MCAST_LEAVE_SOURCE_GROUP, int given
