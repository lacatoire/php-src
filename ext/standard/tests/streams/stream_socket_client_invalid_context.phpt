--TEST--
stream_socket_client() does not connect when the context is invalid
--FILE--
<?php

$server = stream_socket_server('tcp://127.0.0.1:0');
$address = stream_socket_get_name($server, false);

try {
    stream_socket_client($address, $errno, $errstr, 1, STREAM_CLIENT_CONNECT, fopen('php://memory', 'r'));
} catch (TypeError $e) {
    echo $e->getMessage(), "\n";
}

$read = [$server];
$write = $except = null;
var_dump(stream_select($read, $write, $except, 0, 200000));

?>
--EXPECT--
stream_socket_client(): supplied resource is not a valid Stream-Context resource
int(0)
