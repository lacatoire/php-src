--TEST--
stream_socket_client() reports a message when the TLS handshake fails after the connect
--EXTENSIONS--
openssl
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:0', $errorCode, $errorMessage);
$address = stream_socket_get_name($server, false);

$client = @stream_socket_client("ssl://$address", $errorCode, $errorMessage, 0.2);
var_dump($client, $errorMessage);
?>
--EXPECT--
bool(false)
string(20) "Cannot enable crypto"
