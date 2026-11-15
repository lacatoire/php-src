--TEST--
stream_socket_recvfrom() does not build $address when nothing was received
--FILE--
<?php
$server = stream_socket_server('udp://127.0.0.1:0', $errno, $errstr, STREAM_SERVER_BIND);
$client = stream_socket_client('udp://' . stream_socket_get_name($server, false));
fwrite($client, 'x');
var_dump(stream_socket_recvfrom($server, 10, 0, $address));
var_dump($address === stream_socket_get_name($client, false));

stream_set_blocking($server, false);
for ($i = 0; $i < 100; $i++) {
    $data = @stream_socket_recvfrom($server, 10, 0, $address);
}
var_dump($data, $address);
?>
--EXPECT--
string(1) "x"
bool(true)
bool(false)
NULL
