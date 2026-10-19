--TEST--
Ports outside 0-65535 are rejected instead of wrapping modulo 65536
--FILE--
<?php
$srv = stream_socket_server("tcp://127.0.0.1:0");
[$h, $p] = explode(":", stream_socket_get_name($srv, false));

foreach (['fsockopen', 'pfsockopen'] as $fn) {
    try {
        $fn($h, $p + 65536, $errno, $errstr, 1);
    } catch (ValueError $e) {
        echo $e->getMessage(), "\n";
    }
}

var_dump(@stream_socket_client("tcp://$h:" . ($p + 65536), $errno, $errstr, 1));
echo $errstr, "\n";
var_dump(@stream_socket_server("tcp://127.0.0.1:65536", $errno, $errstr));
echo $errstr, "\n";
var_dump(@stream_socket_server("tcp://127.0.0.1:-1", $errno, $errstr));
echo $errstr, "\n";
var_dump(@stream_socket_client("tcp://[::1]:65536", $errno, $errstr, 1));
echo $errstr, "\n";
var_dump(is_resource(stream_socket_server("tcp://127.0.0.1:0")));
?>
--EXPECTF--
fsockopen(): Argument #2 ($port) must be less than or equal to 65535
pfsockopen(): Argument #2 ($port) must be less than or equal to 65535
bool(false)
Invalid port "%d"
bool(false)
Invalid port "65536"
bool(false)
Invalid port "-1"
bool(false)
Invalid port "65536"
bool(true)
