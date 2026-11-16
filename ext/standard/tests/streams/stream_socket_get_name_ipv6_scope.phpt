--TEST--
stream_socket_get_name(): the zone of a link-local IPv6 address is kept
--SKIPIF--
<?php
$found = false;
foreach (net_get_interfaces() as $name => $interface) {
    foreach ($interface['unicast'] ?? [] as $unicast) {
        if (str_starts_with($unicast['address'] ?? '', 'fe80:')) {
            $found = true;
        }
    }
}
if (!$found) {
    die('skip no link-local IPv6 address');
}
?>
--FILE--
<?php

foreach (net_get_interfaces() as $name => $interface) {
    foreach ($interface['unicast'] ?? [] as $unicast) {
        if (str_starts_with($unicast['address'] ?? '', 'fe80:')) {
            $address = explode('%', $unicast['address'])[0];
            break 2;
        }
    }
}

$server = stream_socket_server("tcp://[$address%$name]:0", $errno, $errstr);
$serverName = stream_socket_get_name($server, false);
var_dump(preg_match('/^\[fe80:[0-9a-f:]+%\d+\]:\d+$/', $serverName));

$client = stream_socket_client("tcp://$serverName", $errno, $errstr, 1);
var_dump(is_resource($client));
var_dump(preg_match('/^\[fe80:[0-9a-f:]+%\d+\]:\d+$/', stream_socket_get_name($client, true)));

?>
--EXPECT--
int(1)
bool(true)
int(1)
