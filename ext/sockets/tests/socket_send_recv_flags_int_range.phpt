--TEST--
socket_recv(), socket_recvfrom(), socket_send() and socket_sendto(): $flags outside the int range is rejected
--EXTENSIONS--
sockets
--FILE--
<?php

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, '127.0.0.1', 0);
socket_getsockname($socket, $address, $port);
socket_connect($socket, $address, $port);

foreach ([1 << 32, PHP_INT_MAX, PHP_INT_MIN] as $flags) {
    $calls = [
        'socket_send' => fn() => socket_send($socket, 'data', 4, $flags),
        'socket_sendto' => fn() => socket_sendto($socket, 'data', 4, $flags, $address, $port),
        'socket_recv' => fn() => socket_recv($socket, $buffer, 4, $flags),
        'socket_recvfrom' => fn() => socket_recvfrom($socket, $buffer, 4, $flags, $from, $fromPort),
    ];
    foreach ($calls as $name => $call) {
        try {
            $call();
        } catch (Throwable $e) {
            echo $e::class, ': ', $e->getMessage(), "\n";
        }
    }
}

// Nothing was sent, a valid call still works
var_dump(socket_send($socket, 'data', 4, 0));
var_dump(socket_recv($socket, $buffer, 4, 0));
var_dump($buffer);

?>
--EXPECT--
ValueError: socket_send(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_sendto(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_recv(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_recvfrom(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_send(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_sendto(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_recv(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_recvfrom(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_send(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_sendto(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_recv(): Argument #4 ($flags) must be between -2147483648 and 2147483647
ValueError: socket_recvfrom(): Argument #4 ($flags) must be between -2147483648 and 2147483647
int(4)
int(4)
string(4) "data"
