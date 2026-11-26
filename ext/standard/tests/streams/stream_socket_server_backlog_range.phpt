--TEST--
stream_socket_server(): the backlog context option is clamped to the int range and null means the default
--SKIPIF--
<?php
if (PHP_OS_FAMILY !== 'Linux') {
    die('skip the accept queue behaviour is Linux specific');
}
?>
--FILE--
<?php

/* Count the connections a server that never accepts takes before they start to time out. */
function queued_connections($backlog): int {
    $options = $backlog === 'unset' ? [] : ['backlog' => $backlog];
    $context = stream_context_create(['socket' => $options]);
    $server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $context);
    $address = stream_socket_get_name($server, false);
    $clients = [];
    while (count($clients) < 40) {
        $client = @stream_socket_client("tcp://$address", $errno, $errstr, 0.1);
        if ($client === false) {
            break;
        }
        $clients[] = $client;
    }
    return count($clients);
}

$default = queued_connections('unset');
var_dump($default > 20);
var_dump(queued_connections(null) === $default);
var_dump(queued_connections(1 << 32) > 20);
var_dump(queued_connections((1 << 32) + 5) > 20);
var_dump(queued_connections(5) < 10);

?>
--EXPECT--
bool(true)
bool(true)
bool(true)
bool(true)
bool(true)
