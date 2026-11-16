--TEST--
stream_socket_accept() returns a socket without the listener's timed_out flag
--FILE--
<?php

$server = stream_socket_server('tcp://127.0.0.1:0');
$address = stream_socket_get_name($server, false);
stream_set_timeout($server, 0, 200000);
fread($server, 1);
var_dump(stream_get_meta_data($server)['timed_out']);

$client = stream_socket_client('tcp://' . $address);
$accepted = stream_socket_accept($server, 2);
var_dump(stream_get_meta_data($accepted)['timed_out']);
var_dump(stream_get_meta_data($client)['timed_out']);

?>
--EXPECT--
bool(true)
bool(false)
bool(false)
