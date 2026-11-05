--TEST--
socket_getsockname() and socket_getpeername(): abstract AF_UNIX name
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

$name = "\0abstract-" . getmypid();

$server = socket_create(AF_UNIX, SOCK_STREAM, 0);
var_dump(socket_bind($server, $name));
socket_listen($server);

$client = socket_create(AF_UNIX, SOCK_STREAM, 0);
var_dump(socket_connect($client, $name));
$accepted = socket_accept($server);

var_dump(socket_getsockname($server, $address));
var_dump($address === $name);
var_dump(socket_getpeername($client, $address));
var_dump($address === $name);

// A socket without a name still gives an empty string
var_dump(socket_getsockname($client, $address));
var_dump($address);
var_dump(socket_getpeername($accepted, $address));
var_dump($address);

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
string(0) ""
bool(true)
string(0) ""
