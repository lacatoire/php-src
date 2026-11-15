--TEST--
stream_socket_get_name() returns the name of an abstract unix socket
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') die('skip abstract unix sockets are specific to Linux');
?>
--FILE--
<?php
$name = "\0stream_socket_get_name_abstract_" . getmypid();
$server = stream_socket_server("unix://$name");
$client = stream_socket_client("unix://$name");
$accepted = stream_socket_accept($server, 1);

var_dump(stream_socket_get_name($server, false) === $name);
var_dump(stream_socket_get_name($client, true) === $name);
var_dump(stream_socket_get_name($accepted, false) === $name);

/* The unnamed ends still have no name */
var_dump(stream_socket_get_name($client, false));
var_dump(stream_socket_get_name($accepted, true));
?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(false)
bool(false)
