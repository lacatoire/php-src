--TEST--
stream_socket_client() connects with a timeout beyond what the connect limit can add to the current time
--FILE--
<?php
$server = stream_socket_server('tcp://127.0.0.1:0', $errorCode, $errorMessage);
$address = stream_socket_get_name($server, false);

foreach ([2147483, 1.8445e13] as $timeout) {
    $client = stream_socket_client("tcp://$address", $errorCode, $errorMessage, $timeout);
    var_dump(is_resource($client));
    fclose($client);
}
?>
--EXPECT--
bool(true)
bool(true)
