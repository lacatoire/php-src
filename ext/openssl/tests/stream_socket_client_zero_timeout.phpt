--TEST--
stream_socket_client() ssl:// handshake times out when the timeout is 0
--EXTENSIONS--
openssl
--FILE--
<?php
// The server never answers the TLS handshake
$server = stream_socket_server('tcp://127.0.0.1:0');
$address = stream_socket_get_name($server, false);

var_dump(@stream_socket_client("ssl://$address", $errno, $errstr, 0));
?>
--EXPECT--
bool(false)
