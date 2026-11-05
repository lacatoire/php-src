--TEST--
socket_recvmsg(): name with another family than the socket
--EXTENSIONS--
sockets
--SKIPIF--
<?php
$socket = @socket_create(AF_INET6, SOCK_DGRAM, SOL_UDP);
if ($socket === false || !@socket_bind($socket, '::1', 0)) {
    die('skip IPv6 is not available');
}
?>
--FILE--
<?php

function receive(?int $family): array
{
    $rx = socket_create(AF_INET6, SOCK_DGRAM, SOL_UDP);
    socket_bind($rx, '::1', 0);
    socket_getsockname($rx, $address, $port);
    $tx = socket_create(AF_INET6, SOCK_DGRAM, SOL_UDP);
    socket_sendto($tx, 'abc', 3, 0, '::1', $port);
    $name = $family === null ? [] : ['family' => $family];
    $message = ['name' => $name, 'buffer_size' => 16, 'controllen' => 64];
    socket_recvmsg($rx, $message, 0);
    $received = $message['name'];
    unset($received['port']);
    return $received;
}

echo json_encode(receive(AF_INET6)), "\n";
for ($i = 0; $i < 3; $i++) {
    echo json_encode(receive(AF_INET)), "\n";
}

?>
--EXPECT--
{"family":10,"addr":"::1","flowinfo":0,"scope_id":0}
{"family":10,"addr":"::1","flowinfo":0,"scope_id":0}
{"family":10,"addr":"::1","flowinfo":0,"scope_id":0}
{"family":10,"addr":"::1","flowinfo":0,"scope_id":0}
