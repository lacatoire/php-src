--TEST--
socket_create_listen(): a server can restart on its port while the closed connection is in TIME_WAIT
--EXTENSIONS--
sockets
--SKIPIF--
<?php
if (substr(PHP_OS, 0, 3) == 'WIN') {
    die('skip not for Windows');
}
?>
--FILE--
<?php
$listener = socket_create_listen(0);
socket_getsockname($listener, $address, $port);

$client = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($client, '127.0.0.1', $port);

// The server closes first: its side of the connection stays in TIME_WAIT
$accepted = socket_accept($listener);
socket_close($accepted);
socket_close($listener);
socket_close($client);

$restarted = socket_create_listen($port);
var_dump($restarted instanceof Socket);
socket_getsockname($restarted, $address, $restarted_port);
var_dump($restarted_port === $port);
?>
--EXPECT--
bool(true)
bool(true)
