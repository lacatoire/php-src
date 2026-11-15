--TEST--
stream_socket_sendto() rejects an address containing a NUL byte
--FILE--
<?php
$target = stream_socket_server('udp://127.0.0.1:0', $errno, $errstr, STREAM_SERVER_BIND);
$port = explode(':', stream_socket_get_name($target, false))[1];
$sender = stream_socket_server('udp://127.0.0.1:0', $errno, $errstr, STREAM_SERVER_BIND);
stream_set_blocking($target, false);

var_dump(stream_socket_sendto($sender, 'first', 0, "127.0.0.1\0example.org:$port"));
var_dump(stream_socket_sendto($sender, 'second', 0, "127.0.0.1:$port\0junk"));
var_dump(fread($target, 100));
var_dump(stream_socket_sendto($sender, 'third', 0, "127.0.0.1:$port"));
var_dump(fread($target, 100));
?>
--EXPECTF--
Warning: stream_socket_sendto(): Failed to parse `127.0.0.1' into a valid network address in %s on line %d
bool(false)

Warning: stream_socket_sendto(): Failed to parse `127.0.0.1:%d' into a valid network address in %s on line %d
bool(false)
string(0) ""
int(5)
string(5) "third"
