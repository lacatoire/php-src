--TEST--
socket_set_option(): options are dispatched on the level, integer ranges are checked
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') {
    die('skip Linux only');
}
?>
--FILE--
<?php

echo "Level of the option\n";
$tcp = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
var_dump(socket_set_option($tcp, SOL_TCP, 25, 1024)); // TCP_NOTSENT_LOWAT, same number as SO_BINDTODEVICE
var_dump(socket_get_option($tcp, SOL_TCP, 25));
@socket_set_option($tcp, SOL_TCP, 51, 0); // same number as SO_ATTACH_REUSEPORT_CBPF
var_dump(socket_get_option($tcp, SOL_TCP, 27)); // TCP_SAVE_SYN stays off

echo "Detaching a reuseport program\n";
$udp = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_set_option($udp, SOL_SOCKET, SO_REUSEPORT, 1);
socket_bind($udp, '127.0.0.1', 0);
var_dump(socket_set_option($udp, SOL_SOCKET, SO_ATTACH_REUSEPORT_CBPF, 36));
var_dump(socket_set_option($udp, SOL_SOCKET, SO_ATTACH_REUSEPORT_CBPF, 0));

echo "Ranges\n";
$cases = [
    [SOL_UDP, UDP_SEGMENT, (1 << 32) + 1200],
    [SOL_UDP, UDP_SEGMENT, 1 << 32],
    [SOL_SOCKET, SO_KEEPALIVE, 1 << 32],
    [(1 << 32) + SOL_SOCKET, SO_REUSEADDR, 1],
    [SOL_SOCKET, (1 << 32) + SO_REUSEADDR, 1],
    [SOL_SOCKET, SO_ATTACH_REUSEPORT_CBPF, (1 << 32) + 36],
];
foreach ($cases as [$level, $option, $value]) {
    try {
        socket_set_option($udp, $level, $option, $value);
    } catch (Throwable $e) {
        echo $e::class, ': ', $e->getMessage(), "\n";
    }
}
var_dump(socket_set_option($udp, SOL_SOCKET, SO_RCVBUF, 4294967295));
var_dump(socket_set_option($tcp, SOL_TCP, TCP_NODELAY, -1));
var_dump(socket_set_option($udp, SOL_UDP, UDP_SEGMENT, 65535));

?>
--EXPECT--
Level of the option
bool(true)
int(1024)
int(0)
Detaching a reuseport program
bool(true)
bool(true)
Ranges
ValueError: socket_set_option(): Argument #4 ($value) must be between 0 and 65535
ValueError: socket_set_option(): Argument #4 ($value) must be between 0 and 65535
ValueError: socket_set_option(): Argument #4 ($value) must be between -2147483648 and 4294967295
ValueError: socket_set_option(): Argument #2 ($level) must be between -2147483648 and 2147483647
ValueError: socket_set_option(): Argument #3 ($option) must be between -2147483648 and 2147483647
ValueError: socket_set_option(): Argument #4 ($value) must be between -2147483648 and 4294967295
bool(true)
bool(true)
bool(true)
