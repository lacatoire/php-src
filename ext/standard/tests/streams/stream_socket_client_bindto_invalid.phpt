--TEST--
stream_socket_client() fails on a "bindto" context option that cannot be parsed
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:0', $errorCode, $errorMessage);
$address = stream_socket_get_name($server, false);

foreach (['127.0.0.2:99999', '127.0.0.2', "127.0.0.2:0\0x", '[::1'] as $bindto) {
    $context = stream_context_create(['socket' => ['bindto' => $bindto]]);
    var_dump(stream_socket_client("tcp://$address", $errorCode, $errorMessage, 2, STREAM_CLIENT_CONNECT, $context));
}

$context = stream_context_create(['socket' => ['bindto' => '127.0.0.1:0']]);
var_dump(is_resource(stream_socket_client("tcp://$address", $errorCode, $errorMessage, 2, STREAM_CLIENT_CONNECT, $context)));
?>
--EXPECTF--
Warning: stream_socket_client(): Unable to connect to tcp://127.0.0.1:%d (%s) in %s on line %d
bool(false)

Warning: stream_socket_client(): Unable to connect to tcp://127.0.0.1:%d (%s) in %s on line %d
bool(false)

Warning: stream_socket_client(): Unable to connect to tcp://127.0.0.1:%d (%s) in %s on line %d
bool(false)

Warning: stream_socket_client(): Unable to connect to tcp://127.0.0.1:%d (%s) in %s on line %d
bool(false)
bool(true)
