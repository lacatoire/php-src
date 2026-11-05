--TEST--
socket_listen(): the default backlog does not limit the server to one pending connection
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') {
    die('skip the backlog is interpreted differently outside Linux');
}
?>
--FILE--
<?php

$parameter = new ReflectionParameter('socket_listen', 'backlog');
var_dump($parameter->getDefaultValueConstantName());

$server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($server, '127.0.0.1', 0);
socket_listen($server);
socket_getsockname($server, $address, $port);

$clients = [];
for ($i = 0; $i < 10; $i++) {
    $client = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_set_nonblock($client);
    @socket_connect($client, $address, $port);
    $clients[] = $client;
}

// Connections that completed the handshake are queued without being accepted
usleep(200000);
$queued = 0;
foreach ($clients as $client) {
    if (socket_get_option($client, SOL_SOCKET, SO_ERROR) === 0 && @socket_getpeername($client, $peer)) {
        $queued++;
    }
}
var_dump($queued);

?>
--EXPECT--
string(9) "SOMAXCONN"
int(10)
